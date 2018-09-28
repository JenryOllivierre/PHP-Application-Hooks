# 26 September 2018: v1.0.0 - v2.0.0

- Package was made fully extensible.
- Filters & Actions trait & interfaces has been added to allow you to only use them if you need to.
- The main hooks class `JenryOllivierre\Hooks\Hooks` has become a class with the final keyword.

### Breaking Change

- Due to `JenryOllivierre\Hooks\Hooks` initially being intended not to be extendible, it has been updated with the final keyword. You'll have to update your application ONLY if you previously extended this class to make your own hooks file. If you need extensibility, extend the `JenryOllivierre\Hooks\HooksFoundation` class. See documentation.
- The contract HooksContract has been renamed. If you previously referenced it, you'll have to change it to `JenryOllivierre\Hooks\Hookable`.