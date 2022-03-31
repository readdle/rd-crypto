const v1 = require('./v1/rdcrypto');
const v2 = require('./v2/rdcrypto');

function RDCrypto(secret, salt) {
    this.v1 = new v1(secret);
    this.v2 = new v2(secret, salt);
}

RDCrypto.prototype.encrypt = function (value) {
    return this.v2.encrypt(value);
};

RDCrypto.prototype.decrypt = function (value) {
    return this.v1.decrypt(this.v2.decrypt(value));
};

module.exports = function (secret, salt) {
    return new RDCrypto(secret, salt);
};
