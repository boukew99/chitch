# Theming

Chitch handles the content of your website, since that is part of the database. This content is then put out as a HTML. HTML determines the semantic meaning of the content, which is crucial for accessibility and indexing. You can theme this content freely, adjust its visual structure to your specific needs, ensuring that your website also looks good. All the theming files reside in `public/theme`.

## Classless CSS
The default way is to use Cascading Style Sheets (CSS) to customize the appearance of your website. CSS allows you to define styles for HTML elements, such as fonts, colors, and layout. In Chitch these files are stored in `public/theme/`. The styles of Chitch apply to the default HTML tags. This funnily enough can be searched with 'class-less CSS', but can be considered classical CSS. This keeps the separation between content and style clear and is a lightweight, reusable approach. For custom layout you can add page-specific style within the PHP page itself.

In the browser you can change these styles using the browser's developer tools (F12). This allows you to quickly test different styles and layouts without having to modify the source code. The changes also update in real-time, providing instant feedback on how your design will look. You can open your browsers developer tools and edit the DOM or CSS file in the browser itself.

You can also use CSS libraries to quickly add pre-designed styles to your website, however these require a bit more setup and configuration and are usually slightly heavier.

## Loading the Theme

The theme is totally handled on the client side. That is also why it makes sense to design in the browser itself. The request flow is as follows.

```
Request -> Chitch -> Content (HTML) -> Browser -> Theme -> Page
```

A request is made to Chitch and content is send back. Inside the content is a link to which stylesheets to use. In our case it will be a CSS file in `theme/` directory. The browser will then fetch this CSS and apply it to the content. This CSS file is cached locally at the client after the first time and thus is easily reused across request.
