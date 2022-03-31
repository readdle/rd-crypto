const secret = "NGYR4rBcywrVLqON";
const salt   = "08c51bfa2b2a4812b1a8582a";
const value  = "value";

test('successful encrypt/decrypt', () => {
    const rdcrypto = require('./rdcrypto')(secret, salt);
    const encrypted = rdcrypto.encrypt(value);
    expect(rdcrypto.decrypt(encrypted)).toBe(value);
});

test('invalid secret provided', () => {
    expect(() => require('./rdcrypto')("aaaaa")).toThrowError(Error);
});

test('invalid encrypted value should be returned as is', () => {
    const rdcrypto = require('./rdcrypto')(secret, salt);
    const encrypted = "-CRYPT-broken-39698c8dee76099a15e754368f1833ae62b837bf0c1709481cac859baa";
    expect(rdcrypto.decrypt(encrypted)).toBe(encrypted);
});

test('previosly encrypted value successfully decrypts', () => {
    const rdcrypto = require('./rdcrypto')(secret, salt);
    const encrypted = "-CRYPT-V2-1deedb92c859bb0f9153efc11130b0dcd83bc4e11452baecc0fe48fbb3d3696b45327ce447";
    expect(rdcrypto.decrypt(encrypted)).toBe(value);
});

test('encrypted value without -CRYPT- marker won\'t be decrypted', () => {
    const rdcrypto = require('./rdcrypto')(secret, salt);
    const encrypted = "-FAKE-d248d6dcc04b8b0786cdd1505f91eb2f014684f9406f2da42502bd5cbf3cf4ad";
    expect(rdcrypto.decrypt(encrypted)).toBe(encrypted);
});

test('test v1 is still compatible with php version', () => {
    const rdcrypto = require('./v1/rdcrypto')(secret);
    const encrypted = "-CRYPT-009aa9c7121aacbef2863d136d9de08f";
    expect(rdcrypto.decrypt(encrypted)).toBe("value");
});

test('test crypto could decrypt v1', () => {
    const rdcrypto = require('./rdcrypto')(secret, salt);
    const encrypted = "-CRYPT-009aa9c7121aacbef2863d136d9de08f";
    expect(rdcrypto.decrypt(encrypted)).toBe("value");
});