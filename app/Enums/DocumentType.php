<?php

namespace App\Enums;

enum DocumentType : string
{
    case Cpf = 'cpf';
    case Cnpj = 'cnpj';
}
