<?php namespace App;
/**
 * Created by PhpStorm.
 * User: yjkwak
 * Date: 2016. 2. 23.
 * Time: 오전 10:44
 */

use Illuminate\Database\Eloquent\Model;

class ArticleFile extends Model
{
    protected $table = 'articleFile';

    /**
     * Custom Primary Key field
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * Can bulk inserting fields
     *
     * @var array
     */
    protected $fillable = [
        'width',
        'height',
        'occurDate',
        'fileName',
        'originFileName',
        'fileDirName',
        'typeCode',
        'ip',
        'updateDate',
        'oriUrlIdxHead',
        'rvssOriUrlIdxHead',
        'oriUrl',
        'type2Code',
        'parentMdUid',
        'parentMdModelName',
        'oldStatus',
        'sourceExist',
        'fileTypeCode',
        'fileType2Code',
        'siteUid',
        'sData',
        'articleUid',
    ];

    /**
     * Using Eloquent ORM's created_at, published_at fields
     *
     * @var bool
     */
    public $timestamps = false;
}