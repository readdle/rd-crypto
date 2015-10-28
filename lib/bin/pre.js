module.exports = function (arguments) {
    var args = arguments.slice(2),
        usageMessage = "Usage: " + arguments[0] + " " + arguments[1] + "secret value";

    if (args.length !== 2) {
        throw new Error(usageMessage);
    }

    if (args[0].length > 16) {
        console.error("Warning! Secret should be 16 character long, trimming...");
        args[0] = args[0].substring(0, 16);
    }

    return args;
};