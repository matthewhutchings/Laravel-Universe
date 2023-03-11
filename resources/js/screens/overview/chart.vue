<template>
  <div id=" row">
    <div class="col-12 p-0">
      <div class="card-header d-flex align-items-center justify-content-between card-bg-secondary mt-4">
        <h2 class="h6 m-0  mt-3">Result Preview</h2>
        <span class="btn btn-light"><span>{{ count }}</span> Results</span>
      </div>
      <div class="card mt-4">
        <div class="d-flex align-items-center justify-content-center  p-5 bottom-radius mb-3">
          <div id="chart">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as d3 from "d3";
export default {
  props: ['dataResults'],
  mounted() {
    // this.drawGraph()
  },
  watch: {
    dataResults: function (newValue, oldValue) {
      // run drawGraph whenever dataResults changes


      d3.select("#chart")
        .selectAll("svg")
        .remove();
      this.drawGraph();
    }
  },
  methods: {
    timer() {
      // Random place for a node to go
      var choices = d3.keys(foci);
      var foci_index = Math.floor(Math.random() * choices.length);
      var choice = d3.keys(foci)[foci_index];

      // Update random node
      var random_index = Math.floor(Math.random() * nodes.length);
      nodes[random_index].cx = foci[choice].x;
      nodes[random_index].cy = foci[choice].y;
      nodes[random_index].choice = choice;

      force.resume();

      // Run it again in a few seconds.
      timeout = setTimeout(this.timer, 400);
    },
    drawGraph() {
      var margin = { top: 16, right: 0, bottom: 0, left: 0 },
        width = 1500 - margin.left - margin.right,
        height = 1000 - margin.top - margin.bottom;



      var node_radius = 3,
        padding = 5,
        cluster_padding = 5,
        num_nodes = this.count;


      if (this.count < 1000)
        node_radius = 5;
      else if (this.count < 5000)
        node_radius = 1;
      else if (this.count < 20000)
        node_radius = 0.5;


      var svg = d3
        .select("#chart")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


      // Foci
      var foci = this.dataResults

      // Create node objects
      var nodes = [];
      Object.keys(foci).forEach(function (key) {
        var count = foci[key].nodes;
        var fociNodes = d3.range(0, count).map(function (o, i) {
          return {
            id: key + "node" + i,
            x: foci[key].x + Math.random(),
            y: foci[key].y + Math.random(),
            radius: node_radius,
            choice: key
          };
        });
        nodes = nodes.concat(fociNodes);
      });



      svg.append("text")
        .attr("x", 50)
        .attr("y", 50)
        .attr("font-size", "20px")
        .attr("fill", "blue")
        .text("Hello, World!");



      // Force-directed layout
      var force = d3.layout
        .force()
        .nodes(nodes)
        .size([width, height])
        .gravity(0)
        .charge(0)
        .friction(0.91)
        .on("tick", tick)
        .start();

      // Draw circle for each node.
      var circle = svg
        .selectAll("circle")
        .data(nodes)
        .enter()
        .append("circle")
        .attr("id", function (d) {
          return d.id;
        })
        .attr("class", "node")
        .attr("cx", function (d) {
          return d.x;
        })
        .attr("cy", function (d) {
          return d.y;
        })
        .attr("r", node_radius)
        .attr("fill", function (d) {
          return foci[d.choice].color;
        });
      // For smoother initial transition to settling spots.
      circle
        .transition()
        .duration(300)
        .delay(function (d, i) {
          return i * 5;
        })
        .attrTween("r", function (d) {
          var i = d3.interpolate(0, d.radius);
          return function (t) {
            return (d.radius = i(t));
          };
        });

      // Run function periodically to make things move.
      function timer() {
        // Random place for a node to go
        var choices = d3.keys(foci);
        var foci_index = Math.floor(Math.random() * choices.length);
        var choice = d3.keys(foci)[foci_index];

        // Update random node
        var random_index = Math.floor(Math.random() * nodes.length);
        nodes[random_index].cx = foci[choice].x;
        nodes[random_index].cy = foci[choice].y;
        nodes[random_index].choice = choice;

        force.resume();

        // Run it again in a few seconds.


        timeout = setTimeout(timer, 50000);
      }


      timeout = setTimeout(timer, 5000);


      //
      // Force-directed boiler plate functions
      //

      function tick(e) {
        circle
          .each(gravity(0.051 * e.alpha))
          .each(collide(0.5))
          .style("fill", function (d) {
            return foci[d.choice].color;
          })
          .attr("cx", function (d) {
            return d.x;
          })
          .attr("cy", function (d) {
            return d.y;
          });
      }

      // Move nodes toward cluster focus.
      function gravity(alpha) {
        return function (d) {
          d.y += (foci[d.choice].y - d.y) * alpha;
          d.x += (foci[d.choice].x - d.x) * alpha;
        };
      }

      // Resolve collisions between nodes.
      function collide(alpha) {
        var quadtree = d3.geom.quadtree(nodes);
        return function (d) {
          var r = d.radius + node_radius + Math.max(padding, cluster_padding),
            nx1 = d.x - r,
            nx2 = d.x + r,
            ny1 = d.y - r,
            ny2 = d.y + r;
          quadtree.visit(function (quad, x1, y1, x2, y2) {
            if (quad.point && quad.point !== d) {
              var x = d.x - quad.point.x,
                y = d.y - quad.point.y,
                l = Math.sqrt(x * x + y * y),
                r =
                  d.radius +
                  quad.point.radius +
                  (d.choice === quad.point.choice ? padding : cluster_padding);
              if (l < r) {
                l = ((l - r) / l) * alpha;
                d.x -= x *= l;
                d.y -= y *= l;
                quad.point.x += x;
                quad.point.y += y;
              }
            }
            return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
          });
        };
      }
    }
  }
}
</script>