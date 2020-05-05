<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_admin extends CI_Controller {

    public function index()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/dashboard');
        $this->load->view('admin/footer');
        
    }
    public function v_siswa()
    {
        $data['tb_siswa'] = $this->Model->tampil_siswa();
        
        $this->load->view('admin/header');
        $this->load->view('admin/v_siswa',$data);
        $this->load->view('admin/footer');
    }
    public function tambah_siswa()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/v_tambah_siswa');
        $this->load->view('admin/footer');
    }
       // tambah kan fungsi upload  untuk semua
       public function upload($name)
       {
           $config['upload_path'] = './assets/images/'; //path folder
           $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
           $config['encrypt_name'] = true; //nama yang terupload nantinya
   
           $this->upload->initialize($config);
           if (!empty($_FILES[$name]['name'])) {
               if ($this->upload->do_upload($name)) {
                   $gbr = $this->upload->data();
                   // Compress Image
                   $config['image_library'] = 'gd2';
                   $config['source_image'] = './assets/images/' . $gbr['file_name'];
                   $config['create_thumb'] = false;
                   $config['maintain_ratio'] = false;
                   $config['quality'] = '60%';
                   $config['width'] = 710;
                   $config['height'] = 420;
                   $config['new_image'] = './assets/images/' . $gbr['file_name'];
                   $this->load->library('image_lib', $config);
                   $this->image_lib->resize();
                   $response['data'] = $gbr['file_name'];
                   $response['status'] = 'success';
                   return $response;
               } else {
                   $response['status'] = 'error';
                   return $response;
                   // redirect('c_admin/V_berita');
               }
   
           } else {
               return $response['status'] = 'image not found';
           }
       }
   
    public function save_siswa()
    {
        $agama =  $this->input->post('agama');
        if ($agama == '0')
        {
            $this->session->set_flashdata('error', 'Anda belum memilih agama');
            redirect('c_admin/tambah_siswa');
        }
        $image = $this->upload('image');
        if ($image['status'] == 'success'){
            $data = array(
                'nik' => $this->input->post('nik'),
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'no_hp' => $this->input->post('no_hp'),
                'agama' => $this->input->post('agama'),
                'image' => $image['data'],
                'tanggal' => date('d-m-Y H:i:s'),
                );
                $this->Model->save_siswa($data);
                $this->session->set_flashdata('success', 'data success in save');
   
                redirect('c_admin/v_siswa');
            }else {
                $this->session->set_flashdata('error', 'Foto yang anda upload tidak sesuai kriteria sisten');
                redirect('c_admin/tambah_siswa');
            }
             
    }

    public function delete_siswa($id)
    {
        $this->Model->delete_siswa($id);
        $this->session->set_flashdata('danger', 'resident data successfully deleted');
        
        redirect('c_admin/v_siswa');
    }
    public function edit_siswa($id)
    {
        $data ['edit'] = $this->Model->edit_siswa($id);
        
        $this->load->view('admin/header');
        $this->load->view('admin/v_edit_siswa',$data);
        $this->load->view('admin/footer');
        

    }

}

?>