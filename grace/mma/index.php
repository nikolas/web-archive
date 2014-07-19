<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
	<meta http-equiv="content-type" content="text/xhtml; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="mma.css" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="feed.atom" />
	<title>Marlon Moonglow's Animator</title>
	</head>

<body>
<div class="header">
	<a href="./">Marlon Moonglow's Animator</a>
</div>

<?php include("include/menu.html");?>

<div class="subheader">
<!--	<span>News <a href="feed.atom"><img alt="atom feed" src="../images/feed.png"></a></span> -->
	<span>News</span>
</div>

<div id="content">
	<h1>3/5/10, 1:27 pm -- Presentation notes</h1>
	<p>
	I'm pretty satisfied with where I've gotten so far. I'm nowhere near finished -- I haven't even started implementing the animation scheme. However, just working on the menu system / mouse and keyboard interaction has been instructive.
	</p>
	<p>
	For example, I have just implemented the <a href="http://github.com/nikolas/mma/blob/master/Bindings.hs#L58">scheme</a> for associating buttons with state changes. These would be simple tasks if I was using a menu toolkit like GTK or QT. Because I'm just writing it using OpenGL it takes more time. I'm also still learning subtleties about Haskell, since this is the first large program I have worked with in this language.
	</p>
	<p>
	There are also some rendering quirks which I have chosen to ignore for now. I know just enough OpenGL to get basically what I want on the screen, but there are some strange things happening with textures that I need to sort out.
	</p>
</div>
<div id="content">
	<h1>26/4/10, 11:58 pm</h1>
	<p>
	Well it turns out I was thinking about the problem of mapping over <code>MmaMenu</code> too much. It occurred to me that all I need is a simple function:
<pre>
buttonMap :: (MmaButton -> MmaButton) -> MmaMenu -> MmaMenu
buttonMap f m = m {
  playButton = f $ playButton m,
  nextSprtButton = f $ nextSprtButton m,
  prevSprtButton = f $ prevSprtButton m,
  nextBgButton = f $ nextBgButton m,
  prevBgButton = f $ prevBgButton m,
  nextFrameButton = f $ nextFrameButton m,
  prevFrameButton = f $ prevFrameButton m,
  saveButton = f $ saveButton m
  }
</pre>
	It would be nice, though, to somehow tell Haskell to "apply this function to every <code>MmaButton</code> in this custom datatype".
	</p>
</div>
<div id="content">
	<h1>19/4/10, 2:02 pm -- Mapping over an arbitrary type</h1>
	<p>
	I've run into a limit of my Haskell knowledge. In order to hook up GUI buttons to mouse events, I would like, in <a href="http://github.com/nikolas/mma/blob/master/Bindings.hs#L53">Bindings.hs</a>, to map over my menu type which contains mainly buttons, but also some windows (for showing the current sprite, current background, and current frame):
<pre>
data MmaMenu = MmaMenu
               {
                 playButton :: MmaButton,

                 -- sprite chooser
                 sprtWindow :: MmaWindow,
                 nextSprtButton :: MmaButton,
                 prevSprtButton :: MmaButton,

                 -- background chooser
                 bgWindow :: MmaWindow,
                 nextBgButton :: MmaButton,
                 prevBgButton :: MmaButton,

                 -- stepper
                 frameWindow :: MmaWindow,
                 nextFrameButton :: MmaButton,
                 prevFrameButton :: MmaButton,

                 saveButton :: MmaButton
               } deriving Show
</pre>
	For sprites this is simple because the sprites in my world state are in a list. Because I'm using a list I can easily filter out only the sprites that are under the mouse using <code>filter</code> and <code>map</code>.
	</p>
	<p>
	In a <a href="./archives/2010/01/20/learning-about-functors.php">previous blog entry</a>, I outlined the notion of a Functor in Haskell. Intuitively, it seems like defining <code>fmap</code> over <code>MmaMenu</code> may help me with this dilemma. But how so? Let's find out the type of <code>fmap</code>:
<pre>
Prelude> :t fmap
fmap :: (Functor f) => (a -> b) -> f a -> f b

Prelude> :t map
map :: (a -> b) -> [a] -> [b]
</pre>
	I compared it to <code>map</code>'s type because sometimes it's easier to understand the class by looking at the instantiation as well. It looks the <code>f</code> in <code>fmap</code> turns into 'list of' when we define it over lists.
	</p>
	<p>
	Now we can imagine the type of our <code>menuMap</code> to be <code>(a -> b) -> MmaMenu a -> MmaMenu b</code>. Will this work? Let's find out!
<pre>
instance Functor MmaMenu where
  fmap f m = m {
    playButton = f (playButton m),
    nextSprtButton = f (nextSprtButton m),
    prevSprtButton = f (prevSprtButton m),
    nextBgButton = f (nextBgButton m),
    prevBgButton = f (prevBgButton m),
    nextFrameButton = f (nextFrameButton m),
    prevFrameButton = f (prevFrameButton m),
    saveButton = f (saveButton m)
  }
</pre>
	Aside from looking very messy, this also doesn't work. Intuitively, I didn't think it would work. Now that I look at the typing, however, I can't really point out the problem. Adding to the mystery, GHC gives me the curious error:
<pre>
Menu.hs:54:17:
    Kind mis-match
    Expected kind `* -> *', but `MmaMenu' has kind `*'
    In the instance declaration for `Functor MmaMenu'
Failed, modules loaded: Util, Rectangle.
</pre>
	I have heard of <a href="http://en.wikipedia.org/wiki/Kind_%28type_theory%29">Kind</a>s, as a sort of meta-type. It looks like I better do some more research. Maybe I'll ask someone who knows Haskell really well.
	</p>
</div>
<div id="content">
	<h1>9/4/10, 3:51 pm </h1>
	<p>
	I added some specifications for the menu today. I haven't implemented anything yet (except for a non-functional play button).
	</p>
	<p>
	Here's a screenshot.
	<a href="./img/mma_allcpu.png">
	<img src="./img/mma_allcpu.png" alt="mma using all the cpu" width="150" class="floatRight"/>
	</a>
	My program uses too much CPU when idling. I need to look at some of the other Haskell OpenGL programs (probably my favorite, <a href="http://hackage.haskell.org/package/SGdemo">SGdemo</a>) for ideas. Maybe I need to find out how to update the screen only when the mouse moves, or something like that.
	</p>
</div>
<div id="content">
	<h1>7/4/10, 4:17 pm -- Planning for spring quarter</h1>
	<p>
	Now that I have some basic structure down, I can start focusing on the program's user experience. Here are my revised spring quarter goals:
	<ul>
	<li> Sketch out and implement a menu system (what should happen when the program opens? buttons are clicked? etc.)
	</ul>
	</p>

	<p>
	I don't think the x86/x86_64 issue is affecting textures, they have worked on x86_64 and not worked on x86. I'm going to ignore the issue for now and just assume it's a problem with certain versions of the Haskell bindings for one of the libraries I'm using (OpenGL, GLUT, and SDL).
	</p>
</div>
<div id="content">
	<h1>11/3/10, 1:37 am -- End of winter quarter</h1>
	<p>
	Well this quarter is over and I learned how to use the basics of OpenGL. Next quarter I can focus more on making my program usable, adding more textures, and eventually create a scheme for saving/loading movies. Right now there are also some major kinks I need to work out.
	<ul>
	<li>Textures are completely white on the only 64-bit OS I've tested on, which is also my main desktop >:(</li>
	<li>When the textures do appear (on 32-bit systems), the bottom left of each one has a strange dark shading to it.
	</ul>
	So basically I need to read about texturing.
	</p>
</div>
<div id="content">
	<h1>21/2/10, 9:32 pm -- Some re-planning</h1>
	<p>
	I've been <a href="http://github.com/nikolas/mma">coding</a>, finally. I wasn't planning on starting until spring quarter, but it'll help me in my research of different graphics libraries. I think I've settled on OpenGL. I was initially wary of OpenGL because I always assumed it would be too complicated to use without reading the entirety of that big <a href="http://www.opengl.org/documentation/red_book/">'red book'</a>. I also used to think that a 3D graphics library would be counter-intuitive for my simple 2D animations. Both of my assumptions have turned out to be fairly true so far, but I'm comforted by my nearly functional toy program. I think I'll be able to wrestle it into a working implementation with some more reading.
	</p>
	<p>
	This week I'll be setting up the user input handling, and maybe parts of the user interface. Next week I'll be learning how to deal with textures for backgrounds and 'actors'.
	</p>
</div>
<?php include("archives/2010/02/16/back-to-imperative-programming.php");?>
<?php include("archives/2010/01/27/an-unecessary-tangent.php");?>
<?php include("archives/2010/01/20/learning-about-functors.php");?>
<?php include("archives/2010/01/17/finally-started-organizing-my-schedule.php");?>

</body>
</html>
