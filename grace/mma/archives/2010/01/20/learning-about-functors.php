<div id="content">
	<h1>20/1/10, 2:12 am -- Learning about <a href="http://en.wikibooks.org/wiki/Haskell/Applicative_Functors#Functors">functors</a></h1>
	<p>
	Did you know that in Haskell, a list is a functor? Well, this surprised me. As I was reading <a href="http://conal.net/papers/push-pull-frp/">Conal Elliott's paper</a> on the <a href="http://haskell.org/haskellwiki/Reactive">Reactive</a> library I lost him not long before he started talking about <a href="http://haskell.org/haskellwiki/Applicative_functor">applicative functors</a>. I soon learned that Functor is just a class, not some intimidating more-abstract version of a function like I had previously imagined whenever my eyes skimmed over that word in #haskell.
	</p>
	<p>
	Here is the definition for the class Functor:
	</p>
<pre>
class Functor f where
    fmap :: (a -> b) -> f a -> f b
</pre>
	<p>
	You might notice that <code>fmap</code>'s type is close to <code>map</code>'s type. <code>map</code> applies a function of type <code>(a -> b)</code> to a list of items <code>[a]</code>, yielding the new list <code>[b]</code>. If you don't know about functors yet, you can think of <code>fmap</code> as a generalization of <code>map</code>. A functor is just a structure that you can map a function onto.
	</p>
</div>
