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
     * @throws \Exception
     */
    public static function update(string $key, $value){
        $setting = self::getSettingByKey($key);
        if($setting){
            $setting->value = $value;
            $setting->save();
        }else{
            throw new SettingNotFoundException("setting with the key : $key not found");
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
            MSettings::where("key",'like',$key.".%")->delete();
        }else{
            MSettings::where("key",'like',$key)->delete();
        }
    }

}