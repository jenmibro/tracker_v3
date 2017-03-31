const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir.config.sourcemaps = false;

elixir(function (mix) {

    mix.styles('style.css');

    mix.scripts(
        [

            'libs/jquery/jquery-2.1.1.min.js',
            'libs/jquery/jquery-ui.min.js',

            'libs/jquery/jquery.easing.min.js',
            'libs/jquery/jquery.bootstrap.wizard.min.js',
            'libs/jquery/jquery.powertip.min.js',
            'libs/jquery/jquery.repeater.min.js',
            'libs/jquery/jquery.stickytabs.js',

            'libs/jquery/jquery.flot.min.js',
            'libs/jquery/jquery.flot.resize.min.js',
            'libs/jquery/jquery.flot.spline.js',
            'libs/jquery/jquery.flot.pie.min.js',
            'libs/jquery/jquery.flot.time.min.js',
            'libs/jquery/jquery.flot.comments.min.js',

            'libs/bootstrap/bootstrap.min.js',
            'libs/bootstrap/bootstrap-multiselect.js',

            'libs/sparkline.js',

            'libs/dataTables/jquery.dataTables.min.js',
            'libs/dataTables/dataTables.bootstrap.js',
            'libs/dataTables/dataTables.tableTools.min.js',
        ],

        'public/js/libs.js', 'resources/assets/js');


    mix.browserify('main.js');
    mix.browserify('platoon.js');
    mix.browserify('census-graph.js');

    mix.webpack('app.js');
});


