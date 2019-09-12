title: Many minor updates

----

We have simplified the way that users log in to Coursesuite, added support for launching apps through an API, and made a bunch of minor changes or bug fixes across our apps.

## Logging on
You might have noticed that to sign in or register for the site, all you need to do is supply a valid email address. You are then assigned a password which you can use once to log into the site. We threw away the need to register and verify an email, then get a password and manage that password - the new approach is compatible with the old and means there's less for you to manage. We always need a valid email address, so that's rolled into the new system.

## API

The Coursesuite API lets you externally authenticate and launch apps. API access will allow multiple concurrent users in the apps, give access to all apps and at their highest tier, and allow a degree of customisation to the appearance of apps. The API will be made available to the public soon!

====

Apps have also had a number of changes and bugfixes.

## Document Ninja

* Converting Google Slides now supported
* Split documents by default (settable by option)
* Other settings added (see little cog at the top right)
* Squashed a long-standing bug with completion of multiple videos
* Automatically scale and optimise large images pasted in / uploaded
* Fixed embedding images and galleries from imgur.com
* Fixed various template issues with Edge browser
* Changed SoundCloud output renderer for responsive design

## Video Ninja

* Added preview to source screen so you can be sure that you're loading the correct video
* Changed some design options for clarity of purpose
* Updated internal templates for better readability (for the code tinkerers out there)
* *Removed* BrightCove support (overhead in maintenance / hardly ever got used). Let us know if you need it!

## CourseBuilder

* Fixed an issue with embedding YouTube / Vimeo content
* Implemented support for forthcoming API
* Many internal fixes... nothing you'd notice (hopefully)

## Scorm Previewer

* Fixed file upload (affected Firefox and Edge)
* Better logging display
* Better resizing options
