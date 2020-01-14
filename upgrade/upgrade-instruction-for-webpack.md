# Nessesary steps to build application 

- Update your composer.json
```diff
     "arvenil/ninja-mutex": "^0.4.1",
-    "bmatzner/jquery-bundle": "^2.2.2",
-    "bmatzner/jquery-ui-bundle": "^1.10.3",
     "commerceguys/intl": "^1.0.0",
     "symfony-cmf/routing-bundle": "^2.0.3",
-    "symfony/assetic-bundle": "^2.8.2",
     "symfony/debug": "^3.4",
     "symfony/web-server-bundle": "^3.4",
+    "symfony/webpack-encore-bundle": "^1.7",
     "symfony/workflow": "^3.4",

```
- Check your config/package/assets.yaml file, they have to contain
```diff
framework:
    assets:
        json_manifest_path: '%kernel.project_dir%/web/build/manifest.json'
```
- Check your config/package/webpack_encore.yaml file, they have to contain
```diff
webpack_encore:
    output_path: '%kernel.project_dir%/web/build'
```
- Move all scripts from src/Resources/scripts to assets/js
- Remove folder assets/js/plugins (plugins are load from npm)
- Update your package.json
```dif
     "name": "shopsys",
+    "scripts": {
+        "dev-server": "encore dev-server",
+        "dev": "encore dev",
+        "watch": "encore dev --watch",
+        "build": "encore production --progress",
+        "trans": "./assets/js/bin/trans.js",
+        "copyFw": "./assets/js/bin/copyFw.js"
+    },
+    "dependencies": {
+        "@babel/parser": "^7.7.7",
+        "@babel/traverse": "^7.7.4",
+        "bazinga-translator": "^2.6.6",
+        "chart.js": "^2.9.3",
+        "codemirror": "^5.49.2",
+        "counterup2": "^1.0.4",
+        "jquery": "^3.4.1",
+        "jquery-hoverintent": "^1.10.1",
+        "jquery-minicolors": "^2.1.10",
+        "jquery-ui": "^1.12.1",
+        "jquery-ui-touch-punch": "^0.2.3",
+        "jquery.cookie": "^1.4.1",
+        "magnific-popup": "^1.1.0",
+        "minilazyload": "^2.3.3",
+        "ncp": "^2.0.0",
+        "nestedSortable": "^1.3.4",
+        "pofile": "^1.1.0",
+        "select2": "^4.0.12",
+        "sessionstorage": "^0.1.0",
+        "slick-carousel": "1.6.0",
+        "waypoints": "^4.0.1"
+    },
     "devDependencies": {
+        "@symfony/webpack-encore": "^0.28.0",
         "autoprefixer": "^9.4.4",
+        "copy-webpack-plugin": "^5.1.1",
+        "core-js": "^3.0.0",
         "es6-promise": "^4.2.5",
         "eslint": "^5.12.0",
+        "event-hooks-webpack-plugin": "^2.1.5",
         "grunt": "^1.0.3",
         "jit-grunt": "^0.10.0",
+        "regenerator-runtime": "^0.13.2",
         "stylelint": "^11.1.1",
-        "time-grunt": "^1.4.0"
+        "time-grunt": "^1.4.0",
+        "webpack-notifier": "^1.6.0"
```

- Copy file [webpack.config.js](https://github.com/shopsys/shopsys/blob/9.0/project-base/webpack.config.js) into your project root
- Copy file [/frontend/validation/validationInit](https://github.com/shopsys/shopsys/blob/9.0/project-base/assets/js/frontend/validation/validationInit.js) into your assets/js
- Copy folder [assets/js/commands](https://github.com/shopsys/shopsys/tree/9.0/project-base/assets/js/commands) into your assets/js folder
- Copy folder [assets/js/bin](https://github.com/shopsys/shopsys/tree/9.0/project-base/assets/js/bin) into your assets/js folder
- Copy folder [assets/js/utils](https://github.com/shopsys/shopsys/tree/9.0/project-base/assets/js/utils) into your assets/js folder 

- Create file ./assets/js/app.js and import in it all your frontend javascripts (you can inspirated on [github](https://github.com/shopsys/shopsys/blob/9.0/project-base/assets/js/app.js)) or app.js can look like this:
```diff
import $ from 'jquery';

import showFormErrorsWindowOnFrontend from './frontend/utils/customizeBundle';
import CustomizeBundle from 'framework/common/validation/customizeBundle';
import Register from 'framework/common/utils/register';

+ import './frontend/cart/cartBox';
+ import './frontend/cart/cartRecalculator';
+ import './frontend/components/ajaxMoreLoader';
+ import './frontend/components/popup';
+ import './frontend/components/responsiveTabs';
+ import './frontend/order/order';
+ import './frontend/order/orderRememberData';
+ import './frontend/order/preview';
+ import './frontend/product/addProduct';
+ import './frontend/product/bestsellingProducts';
+ import './frontend/product/gallery';
+ import './frontend/product/productList.AjaxFilter';
+ import './frontend/product/productList';
+ import './frontend/product/productListCategoryToggler';
+ import './frontend/validation/form/customer';
+ import './frontend/validation/form/order';
+ import './frontend/categoryPanel.js';
+ import './frontend/constant.js';
+ import './frontend/cookies.js';
+ import './frontend/form.js';
+ import './frontend/history.js';
+ import './frontend/honeyPot.js';
+ import './frontend/lazyLoadInit.js';
+ import './frontend/legalConditions.js';
+ import './frontend/login.js';
+ import './frontend/modernizr.custom.js';
+ import './frontend/newsletterSubscriptionForm.js';
+ import './frontend/promoCode.js';
+ import './frontend/rangeSlider.js';
+ import './frontend/responsiveToggle.js';
+ import './frontend/responsiveTooltip.js';
+ import './frontend/safariDetection.js';
+ import './frontend/searchAutocomplete.js';
+ import './frontend/slickInit.js';
+ import './frontend/spinbox.js';
+ import './frontend/spinbox.window.js';
+ import './frontend/url.js';
+ import './frontend/window.js';
+ import './frontend/windowFunctions.js';

import 'framework/common/validation/customizeFpValidator';
import './frontend/validation/validationInit';
import 'framework/common/validation';

CustomizeBundle.showFormErrorsWindow = showFormErrorsWindowOnFrontend;

$(document).ready(function () {
    const register = new Register();
    register.registerNewContent($('body'));
});

$(window).on('popstate', function (event) {
    const state = event.originalEvent.state;
    if (state && state.hasOwnProperty('refreshOnPopstate') && state.refreshOnPopstate === true) {
        location.reload();
    }
});
```
- Rename folder assets/js/custom_admin to assets/js/admin
- Create file ./assets/js/admin/admin.js with this content
```diff
+ import 'framework/admin';
```

- Create file ./assets/js/jquery.js with this content
```diff
+ import 'framework/admin/jquery';
```

- Update your base.html.twig template
```diff
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/styles/index' ~ getDomain().id ~ '_' ~ getCssVersion() ~ '.css') }}" media="screen, projection">
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/styles/print' ~ getDomain().id ~ '_' ~ getCssVersion() ~ '.css') }}" media="print">
 
-    {# bootstrap/tooltip.js must be imported before bootstrap/popover.js #}
+    {{ encore_entry_script_tags('jquery') }}

     {{ importJavascripts([
-        'bundles/bmatznerjquery/js/jquery.min.js',
-        'bundles/bmatznerjquery/js/jquery-migrate.js',
-        'bundles/fpjsformvalidator/js/fp_js_validator.js',
+        'bundles/fpjsformvalidator/js/fp_js_validator.js'
     ]) }}

     {{ js_validator_config() }}
     {{ init_js_validation() }}

     {% block html_body %}{% endblock %}
 
-    {% if app.environment == 'dev' %}
-        <script async src="//localhost:35729/livereload.js"></script>
-    {% endif %}
-
-
-    {{ importJavascripts([
-        'frontend/plugins/*.js',
-        'common/components/escape.js',
-        'common/components/keyCodes.js',
-        'common/register.js',
-        'common/bootstrap/tooltip.js',
-        'common/bootstrap/popover.js',
-        'common/plugins/*.js',
-        'frontend/validation/form/*.js',
-        'common/validation/*.js',
-        'frontend/cart/*.js',
-        'frontend/order/*.js',
-        'frontend/product/*.js',
-        'common/*.js',
-        'frontend/*.js',
-        'common/components/*.js',
-        'frontend/components/*.js',
-    ]) }}
+    {{ encore_entry_script_tags('app') }}
 
     {% block javascripts_bottom %}{% endblock %}
 </body>
```

- Update your gitignore file
```diff

```

# Nessesary steps to build javascripts

- Remove file /assets/js/frontend/responsive.js

- Update your assets/js/frontend/cart/cartBox.js
```diff
+ import Register from 'framework/common/utils/register';

  Shopsys.cartBox.reload = function (event) {

      Ajax.ajax({
        ...
      });
      event.preventDefault();
  };

+ (new Register()).registerCallback(Shopsys.cartBox.init);
```

- Update your assets/js/frontend/categoryPanel.js
```diff
+import Ajax from 'framework/common/utils/ajax';
+import Register from 'framework/common/utils/register';
+import Responsive from './utils/responsive';
+
 (function ($) {
 
-    Shopsys = window.Shopsys || {};
+    const Shopsys = window.Shopsys || {};
     Shopsys.categoryPanel = Shopsys.categoryPanel || {};
 
     Shopsys.categoryPanel.init = function ($container) {
         $container.filterAllNodes('.js-category-collapse-control')
             .on('click', onCategoryCollapseControlClick);
+
+        if (!Responsive.isDesktopVersion()) {
+            $container.filterAllNodes('.js-category-collapse-control').each((index, element) => {
+                if ($(element).parent().siblings('.js-category-list-placeholder').length === 0) {
+                    $(element).addClass('open');
+                }
+            });
+        }
     };
  
     function loadCategoryItemContent ($categoryItem, url) {
-        Shopsys.ajax({
+        Ajax.ajax({
                 $categoryListPlaceholder.replaceWith($categoryList);
                 $categoryList.hide().slideDown('fast');
 
-                Shopsys.register.registerNewContent($categoryList);
+                (new Register()).registerNewContent($categoryList);
             }
         });
     }
 
-    Shopsys.register.registerCallback(Shopsys.categoryPanel.init);
+    (new Register()).registerCallback(Shopsys.categoryPanel.init);
 
 })(jQuery);
```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```

- Update your 
```diff

```
