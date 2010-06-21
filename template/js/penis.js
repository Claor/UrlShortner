// Start animation
Event.observe (window, 'load', function ()
{
    $('overlay').setStyle ({width: document.body.clientWidth, height: document.body.clientHeight});
    Effect.Grow ('overlay');
    Effect.SlideDown ('header', {queue: 'end'});
    Effect.Appear ('gcontainer', {from: 0.0, to: 1.0, queue: 'end'});
    Effect.SlideDown ('footer', {queue: 'end'});
}, true);

// Select text of input that contains generated links
Event.observe (window, 'load', function ()
{
    $A($$('input.url-field')).each (function (obj)
    {
        Event.observe (obj, 'click', function(){ this.select(); }, true);
    });
}, true);
