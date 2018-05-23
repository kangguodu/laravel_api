var gulp = require('gulp');
var yaml = require('gulp-yaml');
var yamlMerge = require('gulp-yaml-merge');
var exec = require('gulp-exec');
//var exec = require('child_process').exec;


gulp.task('yamls_merger',function(){
    var options = {
        continueOnError: false, // default = false, true means don't emit error event
        pipeStdout: false, // default = false, true means stdout is written to file.contents
        customTemplatingThing: "test" // content passed to lodash.template()
    };
    var reportOptions = {
        err: true, // default = true, false means don't write err
        stderr: true, // default = true, false means don't write stderr
        stdout: true // default = true, false means don't write stdout
    }
    return gulp.src('./yamls/**/*.yaml')
        .pipe(exec('swagger-merger -i ./yamls/main_local.yaml -o ./dist/swagger_local.yaml && swagger-merger -i ./yamls/main.yaml -o ./dist/swagger.yaml', options))
        .pipe(exec.reporter(reportOptions));
});


gulp.task('watch',function(){
    gulp.watch('./yamls/**/*.yaml',['yamls_merger']);
});