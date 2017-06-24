module.exports = {
  files: {
    javascripts: {
      joinTo: {
        'app.js': /^node_modules|^src/
      }
    },
    stylesheets: {
      joinTo: 'app.css'
    }
  },
  plugins: {
    babel: {
      presets: ['latest']
    },
    cleancss: {
      removeEmpty: true,
      keepSpecialComments: 0,
      restructure: true
    },
    fingerprint: {
        manifest: './web/manifest.json',
        autoClearOldFiles: true,
        srcBasePath: 'web/',
        hashLength: 15,
        destBasePath: 'web/',
    }
  },
  paths: {
    // Change the "public" path (where the build will go)
    "public": 'web/',
    // We change the "app" path
    'watched': ['src/AppBundle/Resources/public']
  },
  conventions: {
    // With this Brunch will copy all folder in this folder without touching them (img, font, ...)
    'assets': /^src\/AppBundle\/Resources\/public\/assets/
  },
  npm: {
    globals: {
       'jQuery' : 'jquery',
       'bootstrap' : 'bootstrap'
    },
    styles: {
      select2: ['dist/css/select2.css'],
      'bootstrap': ['dist/css/bootstrap.css'],
      'bootstrap-datepicker': ['dist/css/bootstrap-datepicker3.css']
    }
  },
  modules: {
    autoRequire: {
      'app.js': ['main'],
    },
    // This will allow us to require/import JS file without specify ALL the path
    nameCleaner: function (path) {
      return path.replace(/^src\/AppBundle\/Resources\/public\//, '');
    }
  }
};