<?php

namespace App\Account\User\Domain;


enum UserDocumentTypeList: int
{
    case DNI = 0; // Documento Nacional de Identidad (DNI)
    case PASSPORT = 1; // Pasaporte
    case FOREIGN_RESIDENT_CARD = 2; // Carnet de Extranjería
    case RUC = 3; // Registro Único de Contribuyentes (RUC)
    case IDENTITY_CARD = 4; // Cédula de Identidad
}
