# DAM-connector

## Ubiquitous Language

### Dam Asset
DAM representation of the Asset. It's already prepared to be transformed in a PIM Asset.
It consists in an identifier `DamAssetIdentifier`, a collection of values `DamAssetValue` and a locale (that could be null).

A DAM Asset Value contains a property name and a value as string.

### Pim Asset
PIM representation of the Asset with a code and a collection of values.
An Asset is a flexible object that makes it possible to enrich products with images, videos, documents...

An Asset must be part of an Asset Family. That way, it will have its own attributes and lifecycle.

### Asset Structure

An Asset Family gathers a number of Assets that share a common attribute structure. In other words, an asset family can be considered as a template for its assets.
An asset family is made of asset attributes.

An Asset Attribute is a characteristic of an Asset for this Asset Family. It helps to describe and qualify an Asset. 
