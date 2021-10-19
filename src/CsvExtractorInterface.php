<?php
namespace Fgsl;

interface CsvExtractorInterface {
    /**
     * Value treatment and definition of INSERT statement
     */
    public function getValues(array $row): string;

    /**
     * General implementation: 
     * $values = getValues($row);
     * return "INSERT INTO (...) VALUES ($values)";
     */
    public function getSqlStatement(array $row): string;
}