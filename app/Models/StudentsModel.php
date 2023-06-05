<?php
namespace App\Models;

use CodeIgniter\Model;

class StudentsModel extends Model
{
    protected $table      = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'photo', 'address', 'phone', 'cep', 'city', 'uf', 'number', 'password'];
    protected $protectFields = ['password'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required',
        'email' => 'required|valid_email',
        'address' => 'required',
        'phone' => 'required',
        'password' => 'required',
        'cep' => 'required',
        'uf' => 'required',
        'city' => 'required',
        'number' => 'required',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
        ],
        'email' => [
            'required' => 'O campo email é obrigatório.',
            'valid_email' => 'Por favor, insira um endereço de email válido.'
        ],
        'address' => [
            'required' => 'O campo endereço é obrigatório.',
        ],
        'password' => [
            'required' => 'O campo senha é obrigatório.',
        ],
        'cep' => [
            'required' => 'O campo CEP é obrigatório.',
        ],
        'uf' => [
            'required' => 'O campo UF é obrigatório.',
        ],
        'city' => [
            'required' => 'O campo cidade é obrigatório.',
        ],
        'number' => [
            'required' => 'Preencha o número da residência.',
        ],
    ];
}