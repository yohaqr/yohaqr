<?php


function array_find_key(array $array, mixed $searchValue): string|int|false {
    return array_search($searchValue, $array, true);
}