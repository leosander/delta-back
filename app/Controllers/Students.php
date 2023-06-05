<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Students extends ResourceController
{
    protected $modelName = 'App\Models\StudentsModel';
    protected $format    = 'json';

    public function index()
    {
        $students = $this->model->findAll();

        foreach ($students as &$student) {
            unset($student['password']);
        }

        return $this->respond($students);
    }
    
    public function create()
    {
        $request = \Config\Services::request();

        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'phone' => $request->getPost('phone'),
            'cep' => $request->getPost('cep'),
            'uf' => $request->getPost('uf'),
            'city' => $request->getPost('city'),
            'password' => password_hash($request->getPost('password'), PASSWORD_DEFAULT),
            'address' => $request->getPost('address'),
            'number' => $request->getPost('number'),
        ];
        $userId = $this->model->insert($data);

        $file = $request->getFile('photo');
        if ($file) {
            if ($userId) {
                $newName = $userId . '.' . $file->getExtension();
                $file->move(ROOTPATH . 'public/uploads', $newName);
                $data['photo'] = $newName;
                $this->model->update($userId, ['photo' => $newName]);
            }
        }
    
        if (!$userId) {
            return $this->fail($this->model->errors(), 400);
        }
    
        return $this->response->setJSON($userId);
    }
    
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            $this->model->delete($id);
            return $this->respondDeleted($data);
        } else {
            return $this->failNotFound('Nenhum estudante encontrado com o id ' . $id);
        }
    }
    
    public function updateStudent()
    {
        $request = \Config\Services::request();
        $id = $request->getPost('id');
    
        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'phone' => $request->getPost('phone'),
            'cep' => $request->getPost('cep'),
            'uf' => $request->getPost('uf'),
            'city' => $request->getPost('city'),
            'address' => $request->getPost('address'),
            'number' => $request->getPost('number'),
        ];
        
        if ($request->getPost('password')) {
            $data['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        }

        $file = $request->getFile('photo');
    
        if ($file) {
            $existingUser = $this->model->find($id);
    
            if ($existingUser && $existingUser['photo']) {
                $photoPath = ROOTPATH . 'public/uploads/' . $existingUser['photo'];

                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }   
               
            }
            
            $newName = $id . '.' . $file->getExtension();
            $file->move(ROOTPATH . 'public/uploads', $newName);
            $data['photo'] = $newName;
        }
    
        $response = $this->model->update($id, $data);
        return $this->response->setJSON($response);
    }
}