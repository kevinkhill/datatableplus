<?php

namespace Khill\Lavacharts\DataTablePlus;

use \League\Csv\Reader;
use \Khill\Lavacharts\Utils;
use \Khill\Lavacharts\Configs\DataTable;

/**
 * DataTable Factory
 *
 * A Class for creating DataTables for Lavacharts.
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
     * Creates a new DataTablePlus object
     *
     * @return DataTablePlus
     */
    public function __construct($timezone = null)
    {
        parent::__construct($timezone);
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
     * @return \Khill\Lavacharts\DataTable
     */
    public function parseCsv($filepath, $columnTypes = null)
    {
        if (Utils::nonEmptyString($filepath) === false) {
            throw new InvalidFunctionParam(
                $filepath,
                __FUNCTION__,
                'string'
            );
        }

        if (is_array($columnTypes) === false || empty($columnTypes) === true) {
            throw new InvalidFunctionParam(
               $columnTypes,
                __FUNCTION__,
                'array'
            );
        }

        $reader = Reader::createFromPath($filepath);
        $reader->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

        $csvColumns = $reader->fetchOne();

        foreach($columnTypes as $index => $column) {
            if (in_array($column, $this->columnTypes, true) === false) {
                throw new InvalidColumnType(
                   $column,
                   Utils::arrayToPipedString($this->columnTypes)
                );
            }

            $this->addColumnFromStrings($columnTypes[$index], $csvColumns[$index]);
        }

        $csvRows = $reader->setOffset(1)->fetchAll(function ($row) {
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
     * @param  \Khill\Lavacharts\DataTable $datatable
     * @param  string $filepath Path where to output the file
     * @return \Khill\Lavacharts\DataTable
     */
    public function toCsv(DataTable $datatable)
    {
        //
    }
}
