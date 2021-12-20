<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Gundam extends RestController {

    public function __construct()
	{
		parent::__construct();
        $this->load->model('gundam_m','gnd');
	}

	public function index_get()
	{
        $id = $this->get('id');
        if($id === null){
            $p = $this->get('page', true);
            $p = (empty($p) ? 1 : $p);
            $total_data = $this->gnd->count();
            $total_page = ceil($total_data / 5);
            $start = ($p - 1) * 5;
            $list = $this->gnd->get(null, 5, $start);
            if($list){
                $data = [
                    'status' => true,
                    'page' => $p,
                    'total_data' => $total_data,
                    'total_page' => $total_page,
                    'data' => $list
                  ];
            }
            else
            {
                $data = [
                    'status' => false,
                    'msg' => 'Data tidak ditemukan'
                  ];
            }
           

            //$list = $this->gnd->get();
            $this->response($data,RestController::HTTP_OK);
        }
        else{
            $data = $this->gnd->get($id);
            //$list = $this->gnd->get();
            if($data){
                $this->response(['status'=>true, 'data'=>$data],RestController::HTTP_OK);
            }
            else{
                $this->response(['status'=>false, 'msg'=>$id. 'data tidak ditemukan'],RestController::HTTP_NOT_FOU);
            }
            
        }
       
	}

    public function index_post()
    {
      $data = [
        'id' => $this->post('id', true),
        'nama' => $this->post('nama', true),
        'harga' => $this->post('harga', true),
        'grade' => $this->post('grade', true)
      ];
      $simpan = $this->gnd->add($data);
      if ($simpan['status']) {
        $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah ditambahkan'], RestController::HTTP_CREATED);
      } else {
        $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
      }
    }

    public function index_put()
    {
      $data = [
        'id' => $this->post('id', true),
        'nama' => $this->post('nama', true),
        'harga' => $this->post('harga', true),
        'grade' => $this->post('grade', true) 
      ];
      $id = $this->put('id', true);
      if ($id === null) {
        $this->response(['status' => false, 'msg' => 'Masukkan id yang akan dirubah'], RestController::HTTP_BAD_REQUEST);
      }
      $simpan = $this->gnd->update($id, $data);
      if ($simpan['status']) {
        $status = (int)$simpan['data'];
        if ($status > 0)
          $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah dirubah'], RestController::HTTP_OK);
        else
          $this->response(['status' => false, 'msg' => 'Tidak ada data yang dirubah'], RestController::HTTP_BAD_REQUEST);
      } else {
        $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
      }
    }

    public function index_delete()
  {
    $id = $this->delete('id', true);
    if ($id === null) {
      $this->response(['status' => false, 'msg' => 'Masukkan id yang akan dihapus'], RestController::HTTP_BAD_REQUEST);
    }
    $delete = $this->gnd->delete($id);
    if ($delete['status']) {
      $status = (int)$delete['data'];
      if ($status > 0)
        $this->response(['status' => true, 'msg' => $id . ' data telah dihapus'], RestController::HTTP_OK);
      else
        $this->response(['status' => false, 'msg' => 'Tidak ada data yang dihapus'], RestController::HTTP_BAD_REQUEST);
    } else {
      $this->response(['status' => false, 'msg' => $delete['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }
}
