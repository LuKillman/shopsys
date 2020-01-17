# Modern frontend

## Introduction to npm, webpack and webpack encore

A [npm](https://www.npmjs.com/) is a package manager it allows you to install javascripts packages from other developers and to eventually publish your own packages.

A [webpack](https://webpack.js.org/) is a bundler for javascript and friends. Packs many modules into a few bundled assets. Code Splitting allows for loading parts of the application on demand.

A [webpack encore](https://github.com/symfony/webpack-encore) is powerful API for processing & compiling assets built around Webpack.

## What do we use them for?

We use npm to manage and install frontend packages. We use npm also for maintain common scripts at [@shopsys/framework](https://www.npmjs.com/~shopsys_framework).

To compile the code into something the browser understands we run the code through Webpack. These build/compile operations are provided as npm script to make them easy to run.

We configure a webpack with a webpack encore.

## How do we use them?

When working with a javascripts and friends packages you should have a package.json in the root directory of your project. This declares all required dependencies to run your package (In PHP, this is similar to a composer.json file).

To install all dependencies you should run `npm install` in the root directory of your project. This installs all third party dependencies in the /node_modules directory. (In PHP, this is similar to running composer). Every time you pull down new code you should ensure that all dependencies are installed or you will get errors such as Error Cannot find module 'foo' when you try to build your application. Phing target `npm` downloads packages and run build script. To add a new library, use the npm install command (for example, `npm install counterup2`).

Once a dependency is installed you can use it in a JS file in your application. For example, if you install counterup2 you can import and use it:

```js
// assets/frontend/components/counterUpInit.js
import counterUp from 'counterup2';

export default function counterUpInit () {
    document.querySelectorAll('.js-counter').forEach(counterItem => {
        counterUp(counterItem, {
            ...
        })
    }
}
```

When compiling your application the process is clever enough to understand when a dependency has already been imported from a different file - meaning that everything is ultimately only ever imported once. However, by having to import dependencies into each file, you ensure that that particular file will work independently.

If you want to add a new component that will listen to a certain event (for example), you have to import the component in the main file. For project-base, this is the `assets/frontend/app.js` file, for the administration is the` assets/admin/admin.js` file. The addition works just like a component installed over npm except that relative paths are used.

```js
    // assets/frontend/app.js
    import './components/counterUpInit';
    ...
```

When we are editing a javascripts and friends files, the change must go through the bundler (webpack). All javascripts and friends files are built using the `npm run build` command. But it would be impractical if we had to run a command in the console with every change. Therefore we can use `npm run watch` for development. This command checks if a file has changed and if it changes it adds the changes to the resulting bundle. The `npm run watch` command launches the webpack in development mode, which means creating source maps to help you debug your project.

## Some use cases

### I want to edit existing javascripts

- you have to run `npm run watch` in project-base (project root). You can run it in docker or on local (when you have installed npm)
- you can edit files
- (you may notice changes in the console)
- you can test changes (after page reload)

### I want to add new javascript file on frontend

- you have to run `npm run watch` in project-base (project root). You can run it in docker or on local (when you have installed npm)
- you can create new javascript file (path of new file is `assets/frontend/myNewFile.js`)
- you can use this new file in some other file (`import ./frontend/myNewFile.js`)
- or, when file contains global event listener, import new file in `assets/frontend/app.js` (`import ./myNewFile.js`)

### I want to add new javascript file on admin

- you have to run `npm run watch` in project-base (project root). You can run it in docker or on local (when you have installed npm)
- you can create new javascript file (path of new file is `assets/admin/myNewFile.js`)
- you can use this new file in some other file (`import ./admin/myNewFile.js`)
- or, when file contains global event listener, import new file in `assets/admin/admin.js` (`import ./myNewFile.js`)

### I want to add new package from other developer

- you have to stop `npm run watch` (when it is running)
- you can add package via npm `npm install <package-name>`
- you have to run `npm run watch` in project-base (project root). You can run it in docker or on local (when you have installed npm)
- you can use new package (`import <package-name>` in some file)
- you can test changes (after page reload)

### I want to override function from @shopsys/framework common package

For example, we can override method `showFormErrorsWindowOnFrontend` from `@shopsys/framework/common/validation/customizeBundle.js` on frontend.

- you have to run `npm run watch` in project-base (project root). You can run it in docker or on local (when you have installed npm)
- you have to import CustomizeBundle in `assets/frontnd/app.js`
```js
import CustomizeBundle from 'framework/common/validation/customizeBundle';
...
```
- you can prepare new function
```js
const myOverridedShowFormErrorsWindow = (container) => {
    console.log('Hello my overrided showFormErrorsWindow method.');
}
```
- you have to replace original method as your new one
```js
CustomizeBundle.showFormErrorsWindow = myOverridedShowFormErrorsWindow;
```
- you can test changes (after page reload)

Full example might look like this:
```js
import CustomizeBundle from 'framework/common/validation/customizeBundle';

const myOverridedShowFormErrorsWindow = (container) => {
    console.log('Hello my overrided showFormErrorsWindow method.');
}

CustomizeBundle.showFormErrorsWindow = myOverridedShowFormErrorsWindow;
```

This princip is call Monkey Patching and you can read more on [this](https://www.sitepoint.com/pragmatic-monkey-patching/) article.
