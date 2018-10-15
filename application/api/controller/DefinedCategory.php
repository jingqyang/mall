<?php
namespace app\api\controller;
use think\Controller;
use think\db;
class DefinedCategory extends Controller{

    //根据父id获取分类信息
    public function getCategoryByParentId(){

        $category_id = input('post.category_id',0,'intval');

        if(!$category_id){
            $this->error('ID不合法');
        }
        //通过id获取二级分类
        $categorys = model('DefinedCategory')->getNormalCategoryByParentId($category_id);

        if(!$categorys){
            return show(0,'error');
        }
        return show(1,'success',$categorys);
    }

    //获取一级分类信息
    public function getNormalFirstCategory(){
        $defined_category = model('DefinedCategory')->getNormalFirstCategory();
        if($defined_category){
            return show(1,'success',$defined_category   );
        }else{
            return show(0,'error');
        }
    }
}
