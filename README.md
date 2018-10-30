groestlcoind server
================

This library can be used to creating groestlcoin node data
directories with a certain configuration, or to boot
a groestlcoind instance against a certain directory.

The NodeService provides a simple API for this function.

The UnitTestNodeService provides an API for creating
once off regtest nodes, whose data directories will be
cleaned up when the service instance is destructed.

The Server class can also be used as a factory for
producing an `nbobtc/groestlcoind` RPC client configured
to use the running instance.
