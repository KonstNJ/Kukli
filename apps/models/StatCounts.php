<?php

namespace App\Models;

class StatCounts extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $entity;

    /**
     * @var integer
     */
    public $entity_id;

    /**
     * @var integer
     */
    public $counts;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("stat_counts");
        $this->belongsTo(
            'entity_id',
            'App\Models\Category',
            'id',
            [
                'alias' => 'Category',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'category'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\Heroes',
            'id',
            [
                'alias' => 'Heroes',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'heroes'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\Brands',
            'id',
            [
                'alias' => 'Brands',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'brands'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\BrandsUniverse',
            'id',
            [
                'alias' => 'BrandsUniverse',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'brands_universe'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\EntityType',
            'id',
            [
                'alias' => 'EntityType',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'entity_type'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\Producer',
            'id',
            [
                'alias' => 'Producer',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'producer'
                    ]
                ]
            ]
        );
        $this->belongsTo(
            'entity_id',
            'App\Models\Condition',
            'id',
            [
                'alias' => 'Condition',
                'params' => [
                    'order' => 'counts desc',
                    'conditions' => 'entity=:entity:',
                    'bind' => [
                        'entity' => 'condition'
                    ]
                ]
            ]
        );
    }
}