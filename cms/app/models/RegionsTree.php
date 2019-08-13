<?php

/**
 * Created by PhpStorm.
 * User: sylar
 * Date: 2015/9/24
 * Time: 9:31
 */
class RegionsTree {
    //a category node
    public $root;
    //nodes save all of the tree child nodes.
    public $nodes;

    private static function findChildren(Regions $category) {
        return Regions::query()->where('father_id=:father_id:')
            ->bind(array("father_id" => $category->id))
            ->execute();
    }

    private static function setChildren(RegionsTree $tree, RegionsNode $node) {
        $categories = RegionsTree::findChildren($node->category);
        if (isset($categories)) {
            foreach ($categories as $category) {
                $childNode = new RegionsNode();
                $childNode->category = $category;
                $childNode->parent = $node;
                array_push($node->children, $childNode);
                RegionsTree::setChildren($tree, $childNode);
                $tree->nodes [$childNode->category->id] = $childNode;
            }
        }
    }

    /**
     * @param $id
     * @return CategoryTree
     */
    public static function getCategoryTree($id) {
        $tree = new RegionsTree();
        $node = new RegionsNode();
        $category = Regions::findFirst($id);
        //The root node parent is self
        $node->parent = $node;
        $node->category = $category;
        RegionsTree::setChildren($tree, $node);
        $tree->root = $node;
        $tree->nodes[$id] = $node;
        return $tree;
    }

    /**
     * @param $id
     * @return CategoryNode
     *
     */
    public function getCategoryNode($id) {
        return $this->nodes[$id];
    }

    /**
     * @param $id
     * @return String
     */
    public function getNodeName($id) {
        return $this->getCategoryNode($id)->category->name;
    }

    /**
     * @param $id
     * @return return the category of id direct children.
     */
    public function getCategoryChildren($id) {
        return $this->nodes[$id]->children;
    }

    public static function setJsonNode($data) {
        $tmp = [];
        $tmp['v'] = $data->category->id;
        $tmp['n'] = $data->category->name;
        if (count($data->children)) {
            $tmp['s'] = [];
            foreach ($data->children as $d) {
                array_push($tmp['s'], RegionsTree::setJsonNode($d));
            }
        }
        return $tmp;
    }

    public static function getCategoryJson($id) {
        $data = RegionsTree::getCategoryTree($id);
        $jsonarr = RegionsTree::setJsonNode($data->root);
        return $jsonarr;
    }
}