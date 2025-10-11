# Authentication

Any client can connect to the website, but how do you *who* that client is? Not everyone can trusted to change the site right? Only the author or maybe some co-authors. This is where authentication comes into play.

## Passwordless

The most used method to login is to supply a secret only the client knows, a password! But let me ask you, how many passwords do you have? Do you reuse any of them? What to do you do when you forget?

There was a user, who always *forgot their password*. Thus when they were prompted to supply the password they never could. This user's default login method would thus be the "Forgot Password?" link. Each time changing the password, and then **forgetting** it again! Thus in Chitch the "forgot password" method became the **standard** login method.

Thus the user supplies their username, publically known, and their email, stored as a secret. This match is verified, and an email is send with a temporary key. If the user then supplies this key in the *same* browser session, then they can login.

This method relies on email as the identifier, thus your emails security equals the security of the website. Still the website securly stores the email with 1-way encryption, so only the username can leak.

