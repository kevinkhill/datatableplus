<?php

namespace Khill\Lavacharts\DataTablePlus;

use \Khill\Lavacharts\Utils;
use \Khill\Lavacharts\DataTables\DataTable;
use \Khill\Lavacharts\Exceptions\InvalidColumnType;
use \Khill\Lavacharts\Exceptions\InvalidFunctionParam;
use \League\Csv\Reader;
use \League\Csv\Writer;
use \Illuminate\Database\Eloquent\Collection;

/**
 * DataTablePlus
 *
 * An extension of the Lavacharts' DataTable to add extra functionality.
 *
 * This includes:
 *  - parsing csv files to DataTables
 *  - exporting DataTables to csv files
 *  - parsing Eloquent collections to DataTables
 *
 *
 * @category  Class
 * @package   Lavacharts
 * @since     1.0.0
 * @author    Kevin Hill <kevinkhill@gmail.com>
 * @copyright (c) 2015, KHill Designs
 * @link      http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link      http://lavacharts.com                   Official Docs Site
 * @license   http://opensource.org/licenses/MIT MIT
 */
class DataTablePlus extends DataTable
{
    /**
     * Csv File Reader
     *
     * @var \League\Csv\Reader
     */
    protected $reader;

    /**
     * New columns for the datatable.
     *
     * @var array
     */
    protected $newColumns;

    /**
     * Creates a new DataTablePlus object
     *
     * @return DataTablePlus
     */
    public function __construct($timezone = null)
    {
        parent::__construct($timezone);
    }

    /**
     * Sets the CsvReader to use with parsing csv files.
     *
     * @see http://csv.thephpleague.com/
     * @access public
     * @since  1.0.0
     * @param  \League\Csv\Reader $csvReader
     * @return self
     */
    public function setReader(Reader $csvReader)
    {
        $this->reader = $csvReader;

        return $this;
    }

    /**
     * Parses a csv file into a DataTable.
     *
     * Pass in a filepath to a csv file and an array of column types:
     * ['date', 'number', 'number', 'number'] for example and a DataTable
     * will be built.
     *
     * @access public
     * @since  1.0.0
     * @param  string $filepath    Path location to a csv file
     * @param  array  $columnTypes Array of column types to apply to the csv values
     * @throws \Khill\Lavacharts\Exceptions\InvalidFunctionParam
     * @return \Khill\Lavacharts\DataTable
     */
    public function parseCsvFile($filepath, $columnTypes = null)
    {
        if (Utils::nonEmptyString($filepath) === false) {
            throw new InvalidFunctionParam(
                $filepath,
                __FUNCTION__,
                'string'
            );
        }

        $this->addNewColumns($columnTypes);
        $this->setReader(Reader::createFromPath($filepath));

        $this->reader->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);


        $csvColumns = $this->reader->fetchOne();

        foreach($this->newColumns as $index => $column) {
            if (in_array($column, $this->columnTypes, true) === false) {
                throw new InvalidColumnType(
                   $column,
                   Utils::arrayToPipedString($this->columnTypes)
                );
            }

            $this->addColumnFromStrings($columnTypes[$index], $csvColumns[$index]);
        }

        $csvRows = $this->reader->setOffset(1)->fetchAll(function ($row) {
            return array_map(function ($cell) {
                if (is_numeric($cell)) {
                    return $cell + 0;
                } else {
                    return $cell;
                }
            }, $row);
        });

        return $this->addRows($csvRows);
    }

    /**
     * Parses a DataTable into a CSV file.
     *
     * Pass in a DataTable and a csv file will be generated.
     *
     * @access public
     * @since  1.0.0
     * @param  string $filepath Path where to output the file
     * @throws \Khill\Lavacharts\Exceptions\InvalidFunctionParam
     * @return string
     */
    public function toCsv($filepath)
    {
        if (Utils::nonEmptyString($filepath) === false) {
            throw new InvalidFunctionParam(
                $filepath,
                __FUNCTION__,
                'string'
            );
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne($this->getColumnLabels());

        foreach ($this->rows as $row) {
            $rowData = [];

            foreach ($row['c'] as $data) {
                $rowData[] = $data['v'];
            }

            $csv->insertOne($rowData);
        }

        $csv->output($filepath);
    }

    /**
     * Using the toArray() method of the collection, adds as rows to the datatable.
     *
     * Columns must be added with the generic addColumn() method to define the model
     * property for the column id.
     *
     * @access public
     * @param  Collection $collection Collection of models
     * @return self
     */
    public function addRowsFromCollection(Collection $collection)
    {
        $colCount = $this->getColumnCount();

        foreach ($collection->toArray() as $collectionRow) {
            $row = [];

            for ($i = 0; $i < $colCount; $i++) {
                $row[] = $collectionRow[$this->getColumnId($i)];
            }

            $this->addRow($row);
        }

        return $this;
    }

    /**
     * Sets the column types to assign to new datatable.
     *
     * @access protected
     * @param  array Column types to assign to the new DataTable.
     * @throws \Khill\Lavacharts\Exceptions\InvalidFunctionParam
     * @return void
     */
    protected function addNewColumns($columnTypes)
    {
        if (is_array($columnTypes) === false || empty($columnTypes) === true) {
            throw new InvalidFunctionParam(
               $columnTypes,
                __FUNCTION__,
                'array'
            );
        }

        $this->newColumns = $columnTypes;
    }
}
