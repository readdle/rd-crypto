var testData = require("./../data.json");

exports['testEncryption'] = function (test) {
    var rdcrypto = require('../../lib/rdcrypto')(testData.secret);
    var encrypted = rdcrypto.encrypt(testData.decrypted);

    test.equal(encrypted, testData.encrypted);

    var decrypted = rdcrypto.decrypt(encrypted);
    test.equal(decrypted, testData.decrypted);


    var wrongDecrypted = rdcrypto.decrypt(testData.wrongEncrypted);
    test.equal(wrongDecrypted, testData.wrongEncrypted);

    test.done();
};

exports['testInvalidSecretKey'] = function (test) {

    var testConstructor = function (secret) {
            return function () {
                require('../../lib/rdcrypto')(secret)
            }
        },

        errorMessage = 'Secret length should be exactly 16 characters long';

    test.throws(
        testConstructor(testData.invalidSecret1),
        Error,
        errorMessage
    );
    test.throws(
        testConstructor(testData.invalidSecret2),
        Error,
        errorMessage
    );

    test.done();
};

exports['testSecure'] = function (test) {
    var rdcrypto = require('../../lib/rdcrypto')(testData.wrongSecret);

    var decrypted = rdcrypto.decrypt(testData.encrypted);
    test.equal(decrypted, testData.encrypted);

    test.done();
};
