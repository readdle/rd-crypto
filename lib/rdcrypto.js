const crypto = require('crypto');

const IV_LENGTH = 16;
const CRYPT_METHOD = 'aes-128-cbc';
const SECRET_LENGTH = 16; // 128 bit or 16 characters

function RDCrypto(secret) {
    if (secret.length !== SECRET_LENGTH) {
        throw new Error(`Secret length should be exactly ${IV_LENGTH} characters long`);
    }

    this.cryptoMarker = '-CRYPT-';
    this.secret = secret;
}

RDCrypto.prototype.encrypt = function (value) {
    const iv = crypto.randomBytes(IV_LENGTH);
    const cipher = crypto.createCipheriv(CRYPT_METHOD, Buffer.from(this.secret), iv);
    let encrypted = cipher.update(value);
    encrypted = Buffer.concat([encrypted, cipher.final()]);

    return `${this.cryptoMarker}${iv.toString('hex')}${encrypted.toString('hex')}`;
};

RDCrypto.prototype.decrypt = function (value) {
    if (0 !== value.indexOf(this.cryptoMarker)) {
        return value;
    }

    const originalValue = value;
    value = value.substr(this.cryptoMarker.length);

    try {
        const iv = Buffer.from(value.substring(0, IV_LENGTH * 2), "hex");
        const encrypted = Buffer.from(value.substring(IV_LENGTH * 2), "hex");

        const decipher = crypto.createDecipheriv(CRYPT_METHOD, Buffer.from(this.secret), iv);
        let decrypted = decipher.update(encrypted);

        decrypted = Buffer.concat([decrypted, decipher.final()]);
        return decrypted.toString();
    } catch (err) {
        return originalValue;
    }
};

module.exports = function (secret) {
    return new RDCrypto(secret);
};
