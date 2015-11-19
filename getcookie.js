/**
 * In order to access the live sainsbury's site we need to go through a check that verifies if are able to run JS
 * once we run that JS we gonne be redirected to a page that sets some cookies
 * finally, we are able to access the site with the valid cookies
 *
 * - this probably is not an issue on the local sainsburys dev environment ;)
 *
 * this phantomjs script can sort out to present the valid cookies for us
 */
var page = require('webpage').create(),
    system = require('system'),
    address,
    filter,
    cnt = 0;

if (system.args.length !== 3) {
    console.log('Usage: getcookie.js <some URL> <cookie name>');
    phantom.exit(1);
} else {
    address = system.args[1];
    filter = system.args[2];

    page.onResourceRequested = function (req) {
        // skip loading assets
        if ((/http:\/\/.+?\.(jpeg|jpg|png|css)/gi).test(req['url'])) {
            request.abort();
        }
    };

    page.onLoadFinished = function (status) {
        cnt++;
        for (var i = 0; i < page.cookies.length; i++) {
            if (page.cookies[i].name == filter) {
                console.log(page.cookies[i].value);
                phantom.exit();
            }
        }

        if (cnt >= 4) {
          phantom.exit();
        }
    };

    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('Failed to load the address');
        }
    });
}
