<?php
/**
 * ContextHelper.php
 * symfony3
 * Date: 12.06.16
 */

namespace Avanzu\AdminThemeBundle\Helper;

use Avanzu\AdminThemeBundle\Routing\RouteAliasCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class ContextHelper extends \ArrayObject
{
    /**
     * @var RouteAliasCollection
     */
    private $router;

    /**
     * ContextHelper constructor.
     *
     * @param array                $config
     * @param RouteAliasCollection $router
     */
    public function __construct(array $config, RouteAliasCollection $router)
    {
        $this->initialize($config);
        $this->router = $router;
    }

    /**
     * @param array $config
     */
    protected function initialize(array $config = [])
    {
        $resolver = new OptionsResolver();
        $this->configureDefaults($resolver);
        
        if(!empty($config))
        {
            try
            {
                $newConfig = $resolver->resolve($config);
                
                foreach($newConfig as $configKey => $configValue)
                {
                    //if(!is_array($configValue))
                    //{
                    $this->setOption($configKey, $configValue);
                    //}
                    //else @todo Not sure if it is needed handle second level index of arrays
                    //{
                    
                    //}
                }
            }
            catch(UndefinedOptionsException $e)
            {
                echo $e->getMessage() . PHP_EOL;
                print_r($config, TRUE);
            }
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->getArrayCopy();
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setOption($name, $value)
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function getOption($name, $default = null)
    {
        return $this->offsetExists($name) ? $this->offsetGet($name) : $default;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasAlias($name)
    {
        return $this->router->hasAlias($name);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function fromAlias($name)
    {
        return $this->router->getRouteByAlias($name);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureDefaults(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'use_twig' => true,
            'use_assetic' => true,
            'options' => [],
            'skin' => 'skin-blue',
            'fixed_layout' => false,
            'boxed_layout' => false,
            'collapsed_sidebar' => false,
            'mini_sidebar' => false,
            'control_sidebar' => true,
            'default_avatar' => 'bundles/avanzuadmintheme/img/avatar.png',
            'widget' => [
                'collapsible_title' => 'Collapse',
                'removable_title' => 'Remove',
                'type' => 'primary',
                'bordered' => true,
                'collapsible' => true,
                'solid' => false,
                'removable' => false,
                'use_footer' => true,
            ],
            'button' => [
                'type' => 'primary',
                'size' => false,
            ],
            'knp_menu' => [
                'enable' => false,
            ],
        ]);
    }
}
