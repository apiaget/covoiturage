<?php

class UrlManager extends CUrlManager
{
    /**
     * @var string the host info used in non-SSL mode
     */
    public $hostInfo = 'http://localhost';
    /**
     * @var string the host info used in SSL mode
     */
    public $secureHostInfo = 'https://localhost';
    /**
     * @var array list of routes that should work only in SSL mode.
     * Each array element can be either a URL route (e.g. 'site/create')
     * or a controller ID (e.g. 'settings'). The latter means all actions
     * of that controller should be secured.
     */
    public $secureRoutes = array();

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        $url = parent::createUrl($route, $params, $ampersand);

        // If already an absolute URL, return it directly
        if (strpos($url, 'http') === 0) {
            return $url;
        }

        // Check if the current protocol matches the expected protocol of the route
        // If not, prefix the generated URL with the correct host info.
        $secureRoute = $this->isSecureRoute($route);
        if (Yii::app()->request->isSecureConnection) {
            return $secureRoute ? $url : $this->hostInfo . $url;
        } else {
            return $secureRoute ? $this->secureHostInfo . $url : $url;
        }
    }

    public function parseUrl($request)
    {
        $route = parent::parseUrl($request);

        // Perform a 301 redirection if the current protocol
        // does not match the expected protocol
        $secureRoute = $this->isSecureRoute($route);
        $sslRequest = $request->isSecureConnection;
        if ($secureRoute !== $sslRequest) {
            $hostInfo = $secureRoute ? $this->secureHostInfo : $this->hostInfo;
            if ((strpos($hostInfo, 'https') === 0) xor $sslRequest) {
                $request->redirect($hostInfo . $request->url, true, 301);
            }
        }
        return $route;
    }

    private $_secureMap;

    /**
     * @param string the URL route to be checked
     * @return boolean if the give route should be serviced in SSL mode
     */
    protected function isSecureRoute($route)
    {
        if ($this->_secureMap === null) {
            foreach ($this->secureRoutes as $r) {
                $this->_secureMap[strtolower($r)] = true;
            }
        }
        $route = strtolower($route);
        if (isset($this->_secureMap[$route])) {
            return true;
        } else {
            return ($pos = strpos($route, '/')) !== false
            && isset($this->_secureMap[substr($route, 0, $pos)]);
        }
    }
}

?>