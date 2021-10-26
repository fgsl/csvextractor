# csvextractor

CSV file extractor for relational database tables

Extrator de arquivos CSV para tabelas de banco de dados relacional

## Installation / Instalação

`composer require fgsl/csvextractor`

## Configuration / Configuração

**Configuration file / Arquivo de configuração**

Copy the file [config.php.dist](https://github.com/fgsl/csvextractor/blob/master/config.php.dist) as config.php and fill with the name of CSV file and the database connection parameters.

Copie o arquivo [config.php.dist](https://github.com/fgsl/csvextractor/blob/master/config.php.dist) como config.php e preencha com o nome do arquivo CSV e com os dados de conexão com o banco de dados

**Decorator**

Create a class that implements the interface [CsvExtractorInterface](https://github.com/fgsl/csvextractor/blob/master/src/CsvExtractorInterface.php) with the treatment for CSV file data and the statement INSERT for the table.

Crie uma classe que implemente a interface [CsvExtractorInterface](https://github.com/fgsl/csvextractor/blob/master/src/CsvExtractorInterface.php) com o tratamento de dados do arquivo CSV e o comando INSERT para a tabela.

Example / Exemplo: 

```php
<?php

use Fgsl\CsvExtractorInterface;

class DecoratorMunicipios implements CsvExtractorInterface {
    /**
     * Value treatment and definition of INSERT statement
     */
        public function getValues(array $row): string
        {
            $values = "'" . $row[11]  . "'," . // CODIGO_MUNICIPIO
            "'" . str_replace("'","\'",$row[12]) . "'" . ',' . // NOME_MUNICIPIO
            "'" . $row[0] . "'" . ',' . // CODIGO_UF
            "'" . $row[1] . "'" . ',' . // NOME_UF
            $row[4]; // CODIGO_IBGE 
            return $values;
        }
    /**
     * General implementation: 
     * $values = getValues($row);
     * return "INSERT INTO (...) VALUES ($values)";
     */
        public function getSqlStatement(array $row): string
        {
                $values = $this->getValues($row);
                $sql = "INSERT INTO municipios(CODIGO_MUNICIPIO, NOME_MUNICIPIO, CODIGO_UF, NOME_UF, CODIGO_IBGE) VALUES ($values)";
                return $sql;
        }
}
```

## Use / Uso

Create a script PHP that imports the Composer `autoload.php`, instances the class `CsvExtractor` and calls the method `extractData`.

Crie um script PHP que importe o `autoload.php` do Composer, instancie a classe `CsvExtractor` e chame o método `extractData`.

Example / Exemplo:

```php
?php
include 'vendor/autoload.php';
include 'DecoratorMunicipios.php';

use Fgsl\CsvExtractor;

$csvExtractor = new CsvExtractor(include 'config.php',new DecoratorMunicipios());
$csvExtractor->extractData();
```

This script it that must be executed to extract the data from CSV file to database table.

Este script é que será executado para extrair os dados do arquivo CSV para a tabela do banco de dados.
