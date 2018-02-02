module.exports = function() {
    var sourcePath = './src/';

    var config = {
            sourceFiles   : {
                scss: sourcePath + '/**/*.scss',
                js  : sourcePath + '/**/*.js'
            },
            destination   : './dist/'
        };

    return config;
};
