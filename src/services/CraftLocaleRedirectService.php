<?php
/**
 * CraftLocaleRedirect plugin for Craft CMS 3.x
 *
 * Locale auto changer
 *
 * @link      petter.me
 * @copyright Copyright (c) 2017 Petter
 */

namespace petterruud\craftlocaleredirect\services;

use petterruud\craftlocaleredirect\CraftLocaleRedirect;

use Craft;
use craft\base\Component;

use yii\web\Cookie;

/**
 * CraftLocaleRedirect Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Petter
 * @package   CraftLocaleRedirect
 * @since     1
 */
class CraftLocaleRedirectService extends Component
{

  protected $path;
  protected $querystring;
  protected $expires;
  // Public Methods
  // =========================================================================

   /**
   * Redirect to provided locale
   * @param string $locale
   */
  public static function redirectToLocale($locale)
  {
    $url = self::newUrl($locale);
    self::setCookie('locale', $locale);
    Craft::$app->response->redirect($url, 302);
  }
  /**
   * Tries to find a match between the browser's preferred locales and the
   * site's configured locales.
   * Craft provides getTranslatedBrowserLanguage(), but it matches against all
   * of Craft's application locales using getAppLocaleIds()
   *
   * @return string
   */
  public static function getBrowserLanguageMatch()
  {
    $browserLanguages = array(Craft::$app->getRequest()->getAcceptableLanguages());
    if ($browserLanguages) {
      $siteLocales = Craft::$app->sites;
      foreach ($siteLocales as $siteLocale) {
        $locale = NULL;
        switch($siteLocale->language) {
          case 'nb-NO':
            $locale = 'no';
          break;
          default:
            $locale = $siteLocale->language;
        }
        echo 'locale=' . $locale;
        if ( in_array($locale, $browserLanguages[0])) {
          echo 'hit';
          return $siteLocale->language;
        }
      }
    }
    return false;
  }
  // Private Methods
  // =========================================================================
  /**
   * Return a new url with locale included
   * @param string $locale
   */
  private static function newUrl($locale)
  {
    $path = Craft::$app->getPath();
    $siteUrl = Craft::$app->request->getBaseUrl();
    $querystring = Craft::$app->request->getQueryString();
    $qs = $querystring ? '?' . $querystring : '';
    echo "<hr>BACON<hr>";
    var_dump($siteUrl);
    var_dump($querystring);
    var_dump($qs);

    //return 'http://vg.no';
    // UrlHelper::getsiteurl(($path, null, null, $locale) . $qs;
  }
  /**
   * Set a cookie
   * @param string $name
   * @param string $value
   * @param int $expire
   * @param string $path
   * @param string $domain
   * @param mixed $secure
   * @param mixed $httponly
   */
  private static function setCookie($name = "", $value = "", $expire = 0, $path = "/", $domain = "", $secure = false, $httponly = false)
  {
    $expire = time() + 60 * 60 * 24 * 365; // 1 year
    setcookie($name, $value, (int) $expire, $path, $domain, $secure, $httponly);
    $_COOKIE[$name] = $value;
  }
}
