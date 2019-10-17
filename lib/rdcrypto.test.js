const secret = "NGYR4rBcywrVLqON";
const value = "value";

test('successful encrypt/decrypt', () => {
    const rdcrypto = require('./rdcrypto')(secret);
    const encrypted = rdcrypto.encrypt(value);
    expect(rdcrypto.decrypt(encrypted)).toBe(value);
});

test('invalid secret provided', () => {
    expect(() => require('./rdcrypto')("aaaaa")).toThrowError(Error);
});

test('invalid encrypted value should be returned as is', () => {
    const rdcrypto = require('./rdcrypto')(secret);
    const encrypted = "-CRYPT-broken-39698c8dee76099a15e754368f1833ae62b837bf0c1709481cac859baa";
    expect(rdcrypto.decrypt(encrypted)).toBe(encrypted);
});

test('encrypted value without -CRYPT- marker won\'t be decrypted', () => {
    const rdcrypto = require('./rdcrypto')(secret);
    const encrypted = "-FAKE-d248d6dcc04b8b0786cdd1505f91eb2f014684f9406f2da42502bd5cbf3cf4ad";
    expect(rdcrypto.decrypt(encrypted)).toBe(encrypted);
});
