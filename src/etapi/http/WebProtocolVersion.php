<?php

namespace etapi\http;

enum WebProtocolVersion: int
{
    case HTTP_VERSION_1_0 = 1;
    case HTTP_VERSION_1_1 = 2;
    case HTTP_VERSION_2_0 = 3;
}