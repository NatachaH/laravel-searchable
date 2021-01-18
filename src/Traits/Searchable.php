<?php
namespace Nh\Searchable\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{

  /**
   * Search keyword in columns
   * @param  Builder $query
   * @param  string  $keyword   Keyword to search
   * @param  string  $operator  Operator to use for search
   * @param  boolean  $allMatch  Is keyword in all columns
   * @return Builder
   */
  public function scopeSearch(Builder $query, $keyword, $operator = 'contains', $allMatch = false)
  {
      // Get columns where to search
      $columns = $this->searchable;

      // Define the operator to use
      switch ($operator) {
        case 'start':
          $valueToSearch = $keyword.'%';
          break;

        case 'end':
          $valueToSearch = '%'.$keyword;
          break;

        default:
          $valueToSearch = '%'.$keyword.'%';
          break;
      }

      // Make the search query
      return static::where(function ($query) use ($columns, $valueToSearch, $allMatch) {

          foreach($columns as $column)
          {
              if($allMatch)
              {
                  $query->where($column,'LIKE', $valueToSearch);
              } else {
                  $query->orWhere($column,'LIKE', $valueToSearch);
              }
          }

      });

  }

  /**
   * Search between in column
   * @param  Builder $query
   * @param  string  $column   Column where to search to search
   * @param  float   $from     Search from X value
   * @param  float   $from     Search to X value
   * @return Builder
   */
  public function scopeSearchBetween(Builder $query,$column, $from = 0, $to = 99999999999999999999)
  {
      if($from > 0 && $to > 0)
      {
        return static::whereBetween($column, [$from,$to]);
      } else if($from > 0) {
        return static::where($column, '>=', $from);
      } else if($to > 0) {
        return static::where($column, '<=', $to);
      } else {
        return static::whereNull($column)->orWhere($column,0);
      }
  }


}
