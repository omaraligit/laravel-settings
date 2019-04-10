<?php

namespace OmarAliGit\Settings\Facades;

use Illuminate\Support\Facades\Facade;
use OmarAliGit\Settings\Exceptions\SettingNotFoundException;
use OmarAliGit\Settings\Models\Settings as MSettings;
use phpDocumentor\Reflection\Types\Boolean;

class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }

    /**
     * @param string $key
     * @param string|array $value if array it must be a key / value array the keys are user for naming
     * @throws \Exception
     */
    public static function Save(string $key, $value){
        if(is_array($value)){
            self::saveArray($key,$value);
        }else{
            self::saveString($key,$value);
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws \Exception
     */
    public static function saveString(string $key, string $value)
    {
        $setting = self::getSettingByKey($key);
        if($setting){
            self::update($key,$value);
        }else{
            $setting = new MSettings();
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
        }
    }

    public static function get(string $key){
        $settings = MSettings::where("key","like",$key."%")->get();
        if($settings->count() == 1 && count(explode(".", $settings->first()->key)) <= 1 ){
            return $settings->first()->value;
        }else{
            $settings_array = [];
            foreach ($settings as $setting) {
                $key = $setting->key;
                $value = $setting->value;
                self::set_value($settings_array,$key,$value);
            }
            return $settings_array;
        }

    }

    public static function set_value(&$root, $compositeKey, $value) {
        $keys = explode('.', $compositeKey);
        while(count($keys) > 1) {
            $key = array_shift($keys);
            if(!isset($root[$key])) {
                $root[$key] = array();
            }
            $root = &$root[$key];
        }
    
        $key = reset($keys);
        $root[$key] = $value;
    }

    /**
     * @param string $key
     * @param array $value
     * @throws \Exception
     */
    public static function saveArray(string $key, array $value)
    {
        foreach ($value as $k => $v){
            $k = $key.".".$k;
            if(is_array($v)){
                self::saveArray($k,$v);
            }else{
                $setting = self::getSettingByKey($k);
                if($setting){
                    self::update($k,$v);
                }else{
                    $setting = new MSettings();
                    $setting->key = $k;
                    $setting->value = $v;
                    $setting->save();
                }
            }
        }
    }

    /**
     * @param $key
     * @param string|array $value
     * @param bool $createIfNotFound if the key is not found create it or a notFoundExection will be thrown
     * @throws \Exception
     */
    public static function update(string $key, $value, $createIfNotFound = false){

        if(is_array($value)){
            self::updateArray($key,$value, $createIfNotFound);
        }else{
            self::updateString($key,$value, $createIfNotFound);
        }

    }


    /**
     * 
     */
    public static function updateString($key, $value, $createIfNotFound){
        $setting = self::getSettingByKey($key);
        if($createIfNotFound && is_null($setting)){
            return self::saveString($key, $value);
        }
        if(!$createIfNotFound && is_null($setting)){
            throw new SettingNotFoundException("setting with the key : $key not found");
        }
        if($setting){
            $setting->value = $value;
            $setting->save();
        }else{
            throw new SettingNotFoundException("setting with the key : $key not found");
        }
    }



    /**
     * 
     */
    public static function updateArray($key, $value, $createIfNotFound){
        foreach ($value as $k => $v){
            $k = $key.".".$k;
            if(is_array($v)){
                self::updateArray($k,$v, $createIfNotFound);
            }else{
                self::updateString($k, $v, $createIfNotFound);
            }
        }
    }


    // TODO : make the update work with single key and arrays...



    /**
     * @param $key
     * @return mixed
     */
    public static function getSettingByKey($key)
    {
        return MSettings::where("key",$key)->first();
    }


    /**
     * @param $key
     * @param bool $deleteSubSettings true to delete all the sub settings e.g. (if $key is payment then all payment.* will be deleted)
     */
    public static function delete($key, bool $deleteSubSettings = false){
        if($deleteSubSettings){
            return MSettings::where("key",'like',$key.".%")->delete();
        }else{
            return MSettings::where("key",'like',$key)->delete();
        }
    }

}