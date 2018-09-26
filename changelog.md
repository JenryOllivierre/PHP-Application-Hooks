# 26 September 2018: v1.0.0 - v1.1.0

- Package was made fully extensible.
- Filters & Actions trait & interfaces has been added to allow you to only use them if you need to.
- The main hooks class `JenryOllivierre\Hooks\Hooks` has become a singleton class with the final keyword.

### Breaking Change

- Due to `JenryOllivierre\Hooks\Hooks` becoming a singleton class, you'll have to update your application ONLY if you previously extended this class to make your own hooks file. See the documentation on how to extend the package the new way.