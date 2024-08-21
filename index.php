<?php
    use services\DataBaseService;
    use DTO\IphoneDTO;

    include_once __DIR__ . '/services/DataBaseService.php';
    include_once __DIR__ . '/DTO/IphoneDTO.php';

    $DBS = new DataBaseService();

    $DBS->setIntoBaseByURL(
        url: 'https://dummyjson.com/products/search?q=phone',
        tableName: 'iPhones',
        DTO: new IphoneDTO()
    );
