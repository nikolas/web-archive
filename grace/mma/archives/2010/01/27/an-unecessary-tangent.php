<div id="content">
	<h1>27/1/10, 3:06 am -- An unnecessary tangent?</h1>
	<p>
I've been thinking about how I want to put those pixels on the screen. It looks like <a href=http://raincat.bysusanlin.com/>Raincat</a> uses SDL to load textures and OpenGL to render the textures along with strings and vectors. I don't know all the details because I haven't been messing with Raincat, lately.
	</p>
	<p>
I just finished reading Wolfgang Jeltch's <a href="http://www.informatik.tu-cottbus.de/~jeltsch/research/tfp-2009-paper.pdf">recent overview</a> of his <a href="http://en.wikipedia.org/wiki/Functional_reactive_programming">FRP</a> library, called <a href="http://www.haskell.org/haskellwiki/Grapefruit">Grapefruit</a>. I was fascinated by his notion of a vista, which is a Mealy machine represented as a tree with an infinite number of nodes. This data structure is used to record all possible future combinations of occurrences in an event-driven system.
	</p>
	<p>
Declarative code has always looked <a href="http://hackage.haskell.org/packages/archive/grapefruit-examples/0.0.0.0/doc/html/src/Examples-Grapefruit-Switching.html#mainCircuit">very exotic</a> to me, but I would love to get the hang of it. Unfortunately, there is only support for drawing GUI widgets with the current version of Grapefruit. There is no graphics support right now. Conal Elliott has another Haskell FRP library called <a href="http://www.haskell.org/haskellwiki/Reactive">Reactive</a> that is a little more mature, but <a href="http://www.haskell.org/haskellwiki/Grapefruit/Comparison_to_other_FRP_libraries">not as cool</a>.
	</p>
</div>
