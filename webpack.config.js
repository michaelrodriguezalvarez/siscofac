var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
     .addEntry('proveedor', ['./assets/js/proveedor.js'])
     .addEntry('contrato', ['./assets/js/contrato.js'])
     .addEntry('suplemento', ['./assets/js/suplemento.js'])
     .addEntry('factura', ['./assets/js/factura.js'])
     .addEntry('contrato_comite_contratacion', ['./assets/js/contrato_comite_contratacion.js'])
     .addEntry('suplemento_comite_contratacion', ['./assets/js/suplemento_comite_contratacion.js'])
     //.addStyleEntry('css/app', './assets/css/*.css')
     .addStyleEntry('css/siscofac', ['./assets/css/siscofac.css'])
     // this creates a 'vendor.js' file with jquery and the bootstrap JS module
     .createSharedEntry('vendor',[
         './vendor/components/jquery/jquery.js',
         './vendor/twitter/bootstrap/dist/js/bootstrap.js',
         './vendor/datatables/datatables/media/js/jquery.dataTables.js',
         // you can also extract CSS - this will create a 'vendor.css' file
         './vendor/twitter/bootstrap/dist/css/bootstrap.css',
         './vendor/twitter/bootstrap/dist/css/bootstrap-grid.css',
         './vendor/datatables/datatables/media/css/jquery.dataTables.css',
         //'./vendor/components/font-awesome/css/fontawesome-all.css'
     ])
    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
     .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();