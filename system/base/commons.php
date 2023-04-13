<?php
trait commons{
    /**
     * @param array $data
     * @return void
     */
    public function hydrater(array $data) {
        foreach ($data as $column => $value) {
            $this->columnValue(strtoupper($column), $value);
        }
    }
}