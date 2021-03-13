#!/bin/sh
/mc config host add d3ctf http://oss:10000 d3ctf 'd3ctf@&*P#ssw0rd'
/mc mb d3ctf/bucket102638
/mc policy set download d3ctf/bucket102638
/mc policy set public d3ctf/bucket102638
