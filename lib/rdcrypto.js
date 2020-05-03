const crypto = require('crypto');

const IV_LENGTH = 16;
const CRYPT_METHOD = 'aes-256-gcm';
const SECRET_LENGTH = 32; // 256 bit or 32 characters

function RDCrypto(secret, salt) {
    if (secret.length < IV_LENGTH) {
        throw new Error(`Secret length should be greater than ${IV_LENGTH} characters`);
    }

    this.cryptoMarker = '-CRYPT-V2-';
    this.secret = crypto.pbkdf2Sync(secret, salt, 100, SECRET_LENGTH, 'sha3-256');
}

RDCrypto.prototype.encrypt = function (value) {
    const iv = crypto.randomBytes(IV_LENGTH);
    const cipher = crypto.createCipheriv(CRYPT_METHOD, this.secret, iv);
    let encrypted = cipher.update(value);
    encrypted = Buffer.concat([encrypted, cipher.final()]);
    const tag = cipher.getAuthTag();

    return `${this.cryptoMarker}${iv.toString('hex')}${encrypted.toString('hex')}${tag.toString("hex")}`;
};

RDCrypto.prototype.decrypt = function (value) {
    if (0 !== value.indexOf(this.cryptoMarker)) {
        return value;
    }

    if (value.length < SECRET_LENGTH + IV_LENGTH * 2) {
        return value;
    }

    const originalValue = value;
    value = value.substr(this.cryptoMarker.length);

    try {
        const iv = Buffer.from(value.substring(0, IV_LENGTH * 2), "hex");
        const encrypted = Buffer.from(value.substring(IV_LENGTH * 2, value.length - SECRET_LENGTH), "hex");
        const tag = Buffer.from(value.slice(-SECRET_LENGTH), "hex");

        const decipher = crypto.createDecipheriv(CRYPT_METHOD, this.secret, iv);
        decipher.setAuthTag(tag);
        const decrypted = decipher.update(encrypted);

        return Buffer.concat([decrypted, decipher.final()]).toString();
    } catch (err) {
        return originalValue;
    }
};

module.exports = function (secret, salt) {
    return new RDCrypto(secret, salt);
};
