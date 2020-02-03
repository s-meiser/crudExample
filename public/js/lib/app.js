var fetch = steal.loader.fetch;

steal.loader.fetch = function(load) {
    var loader = this;
    var cacheVersion = new Date().getTime(),
        cacheKey = loader.cacheKey || "version",
        cacheKeyVersion = cacheKey + "=" + cacheVersion;

    load.address = load.address + (load.address.indexOf('?') === -1 ? '?' : '&') + cacheKeyVersion;
    return fetch.call(this, load);
};

import "./steal/polyfill.min";



