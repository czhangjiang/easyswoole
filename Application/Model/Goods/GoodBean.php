<?php
/**
 * Created by PhpStorm.
 * User: zhangweitao
 * Date: 19-3-10
 * Time: 下午4:30
 */

namespace Application\Model\Goods;


use EasySwoole\Spl\SplBean;

class GoodBean extends SplBean
{

    protected $goods_id;
    protected $cat_id;
    protected $goods_sn;
    protected $goods_name;
    protected $agency_id;
    protected $role_id;
    protected $eq_status;
    protected $meid;
    protected $ip;
    protected $goods_name_style;
    protected $click_count;
    protected $brand_id;
    protected $provider_name;
    protected $goods_number;
    protected $goods_weight;
    protected $market_price;
    protected $shop_price;
    protected $eur_price;
    protected $hk_price;
    protected $promote_price;
    protected $promote_start_date;
    protected $promote_end_date;
    protected $warn_number;
    protected $keywords;
    protected $goods_brief;
    protected $goods_desc;
    protected $goods_thumb;
    protected $goods_img;
    protected $original_img;
    protected $is_real;
    protected $extension_code;
    protected $is_on_sale;
    protected $is_alone_sale;
    protected $is_shipping;
    protected $integral;
    protected $add_time;
    protected $sort_order;
    protected $is_delete;
    protected $is_best;
    protected $is_new;
    protected $is_hot;
    protected $is_promote;
    protected $bonus_type_id;
    protected $last_update;
    protected $goods_type;
    protected $seller_note;
    protected $give_integral;
    protected $rank_integral;
    protected $suppliers_id;
    protected $is_check;
    protected $user_id;
    protected $cj_dz;

    /**
     * @return mixed
     */
    public function getGoodsId()
    {
        return $this->goods_id;
    }

    /**
     * @param mixed $goods_id
     */
    public function setGoodsId($goods_id): void
    {
        $this->goods_id = $goods_id;
    }

    /**
     * @return mixed
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * @param mixed $cat_id
     */
    public function setCatId($cat_id): void
    {
        $this->cat_id = $cat_id;
    }

    /**
     * @return mixed
     */
    public function getGoodsSn()
    {
        return $this->goods_sn;
    }

    /**
     * @param mixed $goods_sn
     */
    public function setGoodsSn($goods_sn): void
    {
        $this->goods_sn = $goods_sn;
    }

    /**
     * @return mixed
     */
    public function getGoodsName()
    {
        return $this->goods_name;
    }

    /**
     * @param mixed $goods_name
     */
    public function setGoodsName($goods_name): void
    {
        $this->goods_name = $goods_name;
    }

    /**
     * @return mixed
     */
    public function getAgencyId()
    {
        return $this->agency_id;
    }

    /**
     * @param mixed $agency_id
     */
    public function setAgencyId($agency_id): void
    {
        $this->agency_id = $agency_id;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * @param mixed $role_id
     */
    public function setRoleId($role_id): void
    {
        $this->role_id = $role_id;
    }

    /**
     * @return mixed
     */
    public function getEqStatus()
    {
        return $this->eq_status;
    }

    /**
     * @param mixed $eq_status
     */
    public function setEqStatus($eq_status): void
    {
        $this->eq_status = $eq_status;
    }

    /**
     * @return mixed
     */
    public function getMeid()
    {
        return $this->meid;
    }

    /**
     * @param mixed $meid
     */
    public function setMeid($meid): void
    {
        $this->meid = $meid;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getGoodsNameStyle()
    {
        return $this->goods_name_style;
    }

    /**
     * @param mixed $goods_name_style
     */
    public function setGoodsNameStyle($goods_name_style): void
    {
        $this->goods_name_style = $goods_name_style;
    }

    /**
     * @return mixed
     */
    public function getClickCount()
    {
        return $this->click_count;
    }

    /**
     * @param mixed $click_count
     */
    public function setClickCount($click_count): void
    {
        $this->click_count = $click_count;
    }

    /**
     * @return mixed
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * @param mixed $brand_id
     */
    public function setBrandId($brand_id): void
    {
        $this->brand_id = $brand_id;
    }

    /**
     * @return mixed
     */
    public function getProviderName()
    {
        return $this->provider_name;
    }

    /**
     * @param mixed $provider_name
     */
    public function setProviderName($provider_name): void
    {
        $this->provider_name = $provider_name;
    }

    /**
     * @return mixed
     */
    public function getGoodsNumber()
    {
        return $this->goods_number;
    }

    /**
     * @param mixed $goods_number
     */
    public function setGoodsNumber($goods_number): void
    {
        $this->goods_number = $goods_number;
    }

    /**
     * @return mixed
     */
    public function getGoodsWeight()
    {
        return $this->goods_weight;
    }

    /**
     * @param mixed $goods_weight
     */
    public function setGoodsWeight($goods_weight): void
    {
        $this->goods_weight = $goods_weight;
    }

    /**
     * @return mixed
     */
    public function getMarketPrice()
    {
        return $this->market_price;
    }

    /**
     * @param mixed $market_price
     */
    public function setMarketPrice($market_price): void
    {
        $this->market_price = $market_price;
    }

    /**
     * @return mixed
     */
    public function getShopPrice()
    {
        return $this->shop_price;
    }

    /**
     * @param mixed $shop_price
     */
    public function setShopPrice($shop_price): void
    {
        $this->shop_price = $shop_price;
    }

    /**
     * @return mixed
     */
    public function getEurPrice()
    {
        return $this->eur_price;
    }

    /**
     * @param mixed $eur_price
     */
    public function setEurPrice($eur_price): void
    {
        $this->eur_price = $eur_price;
    }

    /**
     * @return mixed
     */
    public function getHkPrice()
    {
        return $this->hk_price;
    }

    /**
     * @param mixed $hk_price
     */
    public function setHkPrice($hk_price): void
    {
        $this->hk_price = $hk_price;
    }

    /**
     * @return mixed
     */
    public function getPromotePrice()
    {
        return $this->promote_price;
    }

    /**
     * @param mixed $promote_price
     */
    public function setPromotePrice($promote_price): void
    {
        $this->promote_price = $promote_price;
    }

    /**
     * @return mixed
     */
    public function getPromoteStartDate()
    {
        return $this->promote_start_date;
    }

    /**
     * @param mixed $promote_start_date
     */
    public function setPromoteStartDate($promote_start_date): void
    {
        $this->promote_start_date = $promote_start_date;
    }

    /**
     * @return mixed
     */
    public function getPromoteEndDate()
    {
        return $this->promote_end_date;
    }

    /**
     * @param mixed $promote_end_date
     */
    public function setPromoteEndDate($promote_end_date): void
    {
        $this->promote_end_date = $promote_end_date;
    }

    /**
     * @return mixed
     */
    public function getWarnNumber()
    {
        return $this->warn_number;
    }

    /**
     * @param mixed $warn_number
     */
    public function setWarnNumber($warn_number): void
    {
        $this->warn_number = $warn_number;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getGoodsBrief()
    {
        return $this->goods_brief;
    }

    /**
     * @param mixed $goods_brief
     */
    public function setGoodsBrief($goods_brief): void
    {
        $this->goods_brief = $goods_brief;
    }

    /**
     * @return mixed
     */
    public function getGoodsDesc()
    {
        return $this->goods_desc;
    }

    /**
     * @param mixed $goods_desc
     */
    public function setGoodsDesc($goods_desc): void
    {
        $this->goods_desc = $goods_desc;
    }

    /**
     * @return mixed
     */
    public function getGoodsThumb()
    {
        return $this->goods_thumb;
    }

    /**
     * @param mixed $goods_thumb
     */
    public function setGoodsThumb($goods_thumb): void
    {
        $this->goods_thumb = $goods_thumb;
    }

    /**
     * @return mixed
     */
    public function getGoodsImg()
    {
        return $this->goods_img;
    }

    /**
     * @param mixed $goods_img
     */
    public function setGoodsImg($goods_img): void
    {
        $this->goods_img = $goods_img;
    }

    /**
     * @return mixed
     */
    public function getOriginalImg()
    {
        return $this->original_img;
    }

    /**
     * @param mixed $original_img
     */
    public function setOriginalImg($original_img): void
    {
        $this->original_img = $original_img;
    }

    /**
     * @return mixed
     */
    public function getisReal()
    {
        return $this->is_real;
    }

    /**
     * @param mixed $is_real
     */
    public function setIsReal($is_real): void
    {
        $this->is_real = $is_real;
    }

    /**
     * @return mixed
     */
    public function getExtensionCode()
    {
        return $this->extension_code;
    }

    /**
     * @param mixed $extension_code
     */
    public function setExtensionCode($extension_code): void
    {
        $this->extension_code = $extension_code;
    }

    /**
     * @return mixed
     */
    public function getisOnSale()
    {
        return $this->is_on_sale;
    }

    /**
     * @param mixed $is_on_sale
     */
    public function setIsOnSale($is_on_sale): void
    {
        $this->is_on_sale = $is_on_sale;
    }

    /**
     * @return mixed
     */
    public function getisAloneSale()
    {
        return $this->is_alone_sale;
    }

    /**
     * @param mixed $is_alone_sale
     */
    public function setIsAloneSale($is_alone_sale): void
    {
        $this->is_alone_sale = $is_alone_sale;
    }

    /**
     * @return mixed
     */
    public function getisShipping()
    {
        return $this->is_shipping;
    }

    /**
     * @param mixed $is_shipping
     */
    public function setIsShipping($is_shipping): void
    {
        $this->is_shipping = $is_shipping;
    }

    /**
     * @return mixed
     */
    public function getIntegral()
    {
        return $this->integral;
    }

    /**
     * @param mixed $integral
     */
    public function setIntegral($integral): void
    {
        $this->integral = $integral;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->add_time;
    }

    /**
     * @param mixed $add_time
     */
    public function setAddTime($add_time): void
    {
        $this->add_time = $add_time;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sort_order;
    }

    /**
     * @param mixed $sort_order
     */
    public function setSortOrder($sort_order): void
    {
        $this->sort_order = $sort_order;
    }

    /**
     * @return mixed
     */
    public function getisDelete()
    {
        return $this->is_delete;
    }

    /**
     * @param mixed $is_delete
     */
    public function setIsDelete($is_delete): void
    {
        $this->is_delete = $is_delete;
    }

    /**
     * @return mixed
     */
    public function getisBest()
    {
        return $this->is_best;
    }

    /**
     * @param mixed $is_best
     */
    public function setIsBest($is_best): void
    {
        $this->is_best = $is_best;
    }

    /**
     * @return mixed
     */
    public function getisNew()
    {
        return $this->is_new;
    }

    /**
     * @param mixed $is_new
     */
    public function setIsNew($is_new): void
    {
        $this->is_new = $is_new;
    }

    /**
     * @return mixed
     */
    public function getisHot()
    {
        return $this->is_hot;
    }

    /**
     * @param mixed $is_hot
     */
    public function setIsHot($is_hot): void
    {
        $this->is_hot = $is_hot;
    }

    /**
     * @return mixed
     */
    public function getisPromote()
    {
        return $this->is_promote;
    }

    /**
     * @param mixed $is_promote
     */
    public function setIsPromote($is_promote): void
    {
        $this->is_promote = $is_promote;
    }

    /**
     * @return mixed
     */
    public function getBonusTypeId()
    {
        return $this->bonus_type_id;
    }

    /**
     * @param mixed $bonus_type_id
     */
    public function setBonusTypeId($bonus_type_id): void
    {
        $this->bonus_type_id = $bonus_type_id;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * @param mixed $last_update
     */
    public function setLastUpdate($last_update): void
    {
        $this->last_update = $last_update;
    }

    /**
     * @return mixed
     */
    public function getGoodsType()
    {
        return $this->goods_type;
    }

    /**
     * @param mixed $goods_type
     */
    public function setGoodsType($goods_type): void
    {
        $this->goods_type = $goods_type;
    }

    /**
     * @return mixed
     */
    public function getSellerNote()
    {
        return $this->seller_note;
    }

    /**
     * @param mixed $seller_note
     */
    public function setSellerNote($seller_note): void
    {
        $this->seller_note = $seller_note;
    }

    /**
     * @return mixed
     */
    public function getGiveIntegral()
    {
        return $this->give_integral;
    }

    /**
     * @param mixed $give_integral
     */
    public function setGiveIntegral($give_integral): void
    {
        $this->give_integral = $give_integral;
    }

    /**
     * @return mixed
     */
    public function getRankIntegral()
    {
        return $this->rank_integral;
    }

    /**
     * @param mixed $rank_integral
     */
    public function setRankIntegral($rank_integral): void
    {
        $this->rank_integral = $rank_integral;
    }

    /**
     * @return mixed
     */
    public function getSuppliersId()
    {
        return $this->suppliers_id;
    }

    /**
     * @param mixed $suppliers_id
     */
    public function setSuppliersId($suppliers_id): void
    {
        $this->suppliers_id = $suppliers_id;
    }

    /**
     * @return mixed
     */
    public function getisCheck()
    {
        return $this->is_check;
    }

    /**
     * @param mixed $is_check
     */
    public function setIsCheck($is_check): void
    {
        $this->is_check = $is_check;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getCjDz()
    {
        return $this->cj_dz;
    }

    /**
     * @param mixed $cj_dz
     */
    public function setCjDz($cj_dz): void
    {
        $this->cj_dz = $cj_dz;
    }



}