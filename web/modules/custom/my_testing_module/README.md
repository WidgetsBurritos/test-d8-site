# my_testing_module

This module is intentionally broken.

This module should do the following when you navigate to /my-message:
  1. Shows a message for any authenticated users that says:
      "You are logged in"
      - It's actually showing "You _might be_ logged in" instead.
  2. Shows a message for users with the _my super secret privilege_
      permission that says: "You are super special."
      - It's actually showing "You _aren't all that_ special." instead.
  3. Shows a message for users with the _yet another privilege_ permission
      that says "You have yet another privilege."
  4. If multiple scenarios apply, it should should all of the above messages.
      - It's actually only showing one of these messages.
  5. If a user is not logged in, they should get an access forbidden error.
      - It's actually showing them the message shown to authenticated users.
